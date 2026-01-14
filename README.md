# Vebsajt za upravljanje verzijama firmvera

**Ime:** Nikola Zgonjanin

**Indeks:** 77/22 IT

Ovaj projekat predstavlja Laravel veb aplikaciju za kompaniju koja razvija firmver za klijent. Urađena je kao drugi kolokvijum za predmet Uvod u softversko inženjerstvo.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/tests-30%20passed-brightgreen.svg)](tests)

---

## 📋 O projektu

Sistem za upravljanje firmver verzijama i prijavama grešaka, razvijen za kompaniju koja proizvodi firmver za klijente. Aplikacija omogućava evidenciju verzija, preuzimanje firmvera, vođenje prijava grešaka i upravljanje korisnicima sa različitim ulogama.

### Ključne funkcionalnosti

- **Upravljanje korisnicima** - Administratori mogu kreirati, uređivati i brisati korisnike sa različitim ulogama
- **Upravljanje projektima** - Kreiranje i upravljanje projektima firmvera
- **Verzije firmvera** - Dodavanje novih verzija, praćenje changelog-a i stabilnosti
- **Prijave grešaka** - Sistem za prijavu i praćenje problema sa firmver verzijama
- **Autorizacija** - Role-based access control (RBAC) sa tri uloge: administrator, inženjer, klijent
- **Preuzimanje firmvera** - Download funkcionalnost za stabilne verzije

---

## Tehnologije

| Tehnologija | Verzija | Svrha |
|------------|---------|-------|
| **Laravel** | 12.x | PHP web framework |
| **Laravel Breeze** | 2.3+ | Autentifikacija i UI scaffolding |
| **SQLite** | - | Baza podataka |
| **Blade** | - | Templating engine |
| **Laravel Blueprint** | 2.13+ | Code generation |
| **Laravel Pint** | 1.24+ | Code style fixer |
| **PHPUnit** | 11.5+ | Testiranje |
| **Tailwind CSS** | - | Styling |

---

## Struktura projekta

```
USI-Projekat/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Resource kontroleri (User, Project, FirmwareVersion, SupportRequest)
│   │   └── Requests/             # FormRequest validacije
│   ├── Models/                    # Eloquent modeli (User, Project, FirmwareVersion, SupportRequest, Documentation, UserProject)
│   ├── Policies/                 # Autorizacione policy klase
│   └── Providers/                 # Service providers
├── database/
│   ├── migrations/                # Database migracije
│   ├── seeders/                   # Database seederi (Users, Projects, FirmwareVersions, SupportRequests)
│   └── factories/                 # Model factories
├── resources/
│   └── views/                     # Blade template-ovi
│       ├── auth/                  # Login, register, password reset
│       ├── components/            # Reusable Blade komponente
│       ├── layouts/               # App layout i navigacija
│       ├── projects/              # CRUD view-ovi za projekte
│       ├── firmware-versions/     # CRUD view-ovi za verzije
│       ├── support-requests/      # CRUD view-ovi za prijave
│       └── users/                 # CRUD view-ovi za korisnike
├── routes/
│   ├── web.php                    # Web rute (resource + use-case rute)
│   └── auth.php                   # Autentifikacione rute
├── tests/
│   ├── Feature/                   # Feature testovi (30 testova, 76 asercija)
│   └── Unit/                      # Unit testovi
├── .github/
│   └── workflows/
│       └── ci.yml                 # GitHub Actions CI/CD pipeline
└── draft.yaml                     # Blueprint schema za code generation
```

---

## Modeli i relacije

### Glavni modeli

| Model | Opis | Relacije |
|-------|------|----------|
| **User** | Korisnici sistema (admin/inženjer/klijent) | `belongsToMany(Project)`, `hasMany(SupportRequest)` |
| **Project** | Projekti firmvera | `hasMany(FirmwareVersion)`, `belongsToMany(User)`, `hasManyThrough(SupportRequest)` |
| **FirmwareVersion** | Verzije firmvera | `belongsTo(Project)`, `hasMany(SupportRequest, Documentation)` |
| **SupportRequest** | Prijave grešaka | `belongsTo(FirmwareVersion)`, `belongsTo(User)` (created_by, assigned_to) |
| **UserProject** | Pivot tabela za pristup projektima | `belongsTo(User, Project)` |

---

## Use-case rute

### UC1: Prijava problema
```
GET  /firmware-versions/{firmwareVersion}/support/create
POST /support-requests
```
Klijenti i inženjeri mogu prijaviti problem za određenu verziju firmvera.

### UC2: Dodavanje nove verzije firmvera
```
GET  /projects/{project}/firmware/create
POST /firmware-versions
```
Inženjeri mogu dodati novu verziju firmvera projektu.

### UC3: Preuzimanje firmvera
```
GET /firmware-versions/{firmwareVersion}/download
```
Klijenti i inženjeri mogu preuzeti stabilne verzije firmvera.

---

##  Autorizacija i uloge

| Uloga | Dozvole |
|-------|---------|
| **Administrator** | Puni pristup svim funkcionalnostima (CRUD korisnika, projekata, verzija, prijava) |
| **Inženjer** | Dodavanje verzija firmvera, uređivanje prijava grešaka, dodela prijava sebi |
| **Klijent** | Pregled projekata, preuzimanje firmvera, prijava problema |

Autorizacija je implementirana kroz **Laravel Policies** i **middleware** provere.

---

## Testiranje

```bash
# Pokretanje svih testova
php artisan test

# Pokretanje specifičnih testova
php artisan test --filter SupportRequestTest
php artisan test --filter FirmwareVersionTest
```

**Test statistika:**
- 30 testova
- 76 asercija
- 100% prolaznost

---

## Instalacija i pokretanje

### Preduslovi
- PHP 8.2+
- Composer
- Node.js i npm

### Koraci

```bash
# 1. Kloniraj repozitorijum
git clone <repository-url>
cd USI-Projekat

# 2. Instaliraj PHP zavisnosti
composer install

# 3. Instaliraj Node zavisnosti
npm install

# 4. Podesi okruženje
cp .env.example .env
php artisan key:generate

# 5. Pokreni migracije i seed
php artisan migrate:fresh --seed

# 6. Pokreni development server
php artisan serve
npm run dev
```

### Seed podaci

Aplikacija dolazi sa seed podacima:
- **1 administrator**: `marko.petrovic@raf.rs` / `password`
- **2 inženjera**: `ana.jovanovic@raf.rs`, `stefan.nikolic@raf.rs` / `password`
- **2 klijenta**: `milan.stojanovic@raf.rs`, `jovana.popovic@raf.rs` / `password`
- **5 projekata** sa po 4 verzije firmvera
- **5 prijava grešaka** sa različitim statusima

---

## Code Style (Pint)

```bash
# Automatsko formatiranje
composer pint

# Provera bez izmena
php ./vendor/bin/pint --test
```

---

## Blueprint Code Generation

Projekat koristi **Laravel Blueprint** za generisanje modela, migracija i factory-ja.

```bash
# Generisanje iz draft.yaml
php artisan blueprint:build
```

**Generisano Blueprint-om:**
- Modeli: `Project`, `FirmwareVersion`, `Documentation`, `SupportRequest`, `UserProject`
- Migracije: sve tabele osim `users`
- Factory-ji: za sve generisane modele

**Ručno pisano:**
- Kontroleri, FormRequest klase, Policies, Blade view-ovi, Seederi, Testovi

---

## GitHub Actions CI/CD

CI pipeline automatski pokreće:
- PHPUnit testove
- Laravel Pint code style proveru
- Database migracije i seed

Workflow se pokreće na:
- Push na `main`/`master` branch
- Pull request-ove