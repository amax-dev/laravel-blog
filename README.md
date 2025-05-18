# Laravel 12 CMS Starter Kit

[![Laravel](https://img.shields.io/badge/Laravel-12-red)](https://laravel.com/)
[![Vue](https://img.shields.io/badge/Vue-3-green)](https://vuejs.org/)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Ovaj projekat je **custom Laravel 12 + Vue starter kit**, razvijen kao osnova za CMS aplikacije.  
Sadrži gotove module za upravljanje medijima i postovima, koristeći moderne tehnologije i fleksibilnu strukturu spremnu za proširivanje.

🔗 Repo: [github.com/amax-dev/laravel-blog](https://github.com/amax-dev/laravel-blog)

---

## ✨ Ključne funkcionalnosti

- ✅ Laravel 12 (PHP 8.2+)
- ✅ Vue 3 + TypeScript (Composition API)
- ✅ Inertia.js (SPA bez API sloja)
- ✅ Tailwind CSS + shadcn/vue UI komponente
- ✅ Blok editor sa **Tiptap**
- ✅ Media menadžer (Spatie Media Library)
- ✅ Sistem za postove (naslov, sadržaj, istaknuta slika, kategorije, tagovi)
- ✅ Autentifikacija (Laravel Breeze)
- ✅ Osnova za CMS: SEO, više jezika, korisničke uloge, verzije sadržaja...

---

## 🧰 Tehnologije

| Backend       | Frontend       | Ostalo              |
| ------------- | -------------- | ------------------- |
| Laravel 12    | Vue 3          | Tailwind CSS        |
| PHP 8.2+      | Inertia.js     | shadcn/vue komponente |
| Spatie Media Library | TypeScript | Tiptap Editor |

---

## 🚀 Instalacija

```bash
# 1. Kloniraj repozitorijum
git clone https://github.com/amax-dev/laravel-blog.git
cd laravel-blog

# 2. Instaliraj PHP zavisnosti
composer install

# 3. Kopiraj .env fajl i generiši ključ
cp .env.example .env
php artisan key:generate

# 4. Pokreni migracije
php artisan migrate

# 5. Instaliraj JS zavisnosti i pokreni frontend
npm install
npm run dev
