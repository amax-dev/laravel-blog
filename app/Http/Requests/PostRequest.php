<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{

    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'array'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($postId)],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media,id'],
            'featured' => ['boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'video' => ['nullable', 'array'],
            'video.url' => ['nullable', 'url'],
            'video.provider' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => [
                'array',
                function ($attribute, $value, $fail) {
                    $hasId = isset($value['id']);
                    $hasName = isset($value['name']);

                    // Ako tag ima ID, ignorišemo name i provjeravamo samo ID
                    if ($hasId) {
                        if (!is_int($value['id'])) {
                            $fail('ID taga mora biti broj.');
                        }
                        if (!Tag::query()->where('id', $value['id'])->exists()) {
                            $fail('Izabrani tag ne postoji.');
                        }
                    }
                    // Ako nema ID, name je obavezan
                    else if ($hasName) {
                        if (!is_string($value['name'])) {
                            $fail('Naziv taga mora biti string.');
                        }
                        if (strlen($value['name']) < 2 || strlen($value['name']) > 50) {
                            $fail('Naziv taga mora imati između 2 i 50 karaktera.');
                        }
                    }
                    // Ako nema ni ID ni name, greška
                    else {
                        $fail('Tag mora imati id ili name.');
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Naslov je obavezan.',
            'content.required' => 'Sadržaj je obavezan.',
            'slug.required' => 'Slug je obavezan.',
            'slug.unique' => 'Slug mora biti jedinstven.',
            'status.in' => 'Status mora biti "draft" ili "published".',
            'category_id.exists' => 'Kategorija nije validna.',
            'tags.*.array' => 'Tag mora biti objekat.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
