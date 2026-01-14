# Informacioni sistem za upravljanje firmverom

Ovaj projekat predstavlja Laravel veb aplikaciju za kompaniju koja razvija firmver i softver za ugrađene sisteme.  
Cilj sistema je da omogući:

- **Evidenciju verzija firmvera i projekata**
- **Pregled i preuzimanje stabilnih verzija firmvera**
- **Vođenje prijava grešaka (support zahteva)**
- **Upravljanje korisnicima i ulogama (administrator, inženjer, klijent)**

## Tehnologije i alati

Aplikacija je izrađena koristeći:

- **Laravel 12** - PHP web okvir
- **Laravel Breeze** - autentifikacija i osnovni UI scaffolding
- **SQLite** - baza podataka za lokalni razvoj i testiranje
- **Blade** - templating engine za korisnički interfejs
- **Laravel Blueprint** - generisanje modela, migracija i factory-ja
- **Laravel Pint** - code style fixer
- **PHPUnit** - testiranje
- **GitHub Actions** - CI/CD pipeline

## GitHub repozitorijum

**Link:** [GitHub Repo URL - dodati nakon push-a na GitHub]

Repozitorijum sadrži kompletnu istoriju commit-ova sa jasno definisanim fazama razvoja.

## Struktura projekta

### Modeli (4 glavna + pivot)

1. **User** - korisnici sistema (administrator, inženjer, klijent)
2. **Project** - projekti firmvera
3. **FirmwareVersion** - verzije firmvera po projektu
4. **SupportRequest** - prijave grešaka/zahtevi za podršku
5. **Documentation** - dodatna dokumentacija za verzije firmvera
6. **UserProject** - pivot tabela za many-to-many vezu između korisnika i projekata

### Kontroleri (Resource CRUD)

- `UserController` - upravljanje korisnicima (admin only)
- `ProjectController` - upravljanje projektima
- `FirmwareVersionController` - upravljanje verzijama firmvera
- `SupportRequestController` - upravljanje prijavama grešaka

### Use-case rute (3 komada)

1. **UC1: Prijava problema** - `GET /firmware-versions/{firmwareVersion}/support/create` + `POST /support-requests`
   - Klijenti i inženjeri mogu da prijave problem za određenu verziju firmvera
   - Kreira se `SupportRequest` sa statusom `pending`

2. **UC2: Dodavanje nove verzije firmvera** - `GET /projects/{project}/firmware/create` + `POST /firmware-versions`
   - Inženjeri mogu da dodaju novu verziju firmvera projektu
   - Validacija i autorizacija su implementirani

3. **UC3: Download firmvera** - `GET /firmware-versions/{firmwareVersion}/download`
   - Klijenti i inženjeri mogu da preuzmu stabilne verzije firmvera
   - Koristi se `Storage::download()` sa simulacijom preko dummy fajla

### Testovi

Feature testovi su implementirani u:
- `tests/Feature/SupportRequestTest.php` - testiranje prijave problema
- `tests/Feature/FirmwareVersionTest.php` - testiranje dodavanja verzije

**Ukupno:** 30 testova, 76 asercija (svi prolaze)

## Blueprint generisanje

Projekat koristi **Laravel Blueprint** za generisanje modela, migracija i factory-ja. 

### Sadržaj `draft.yaml`:

```yaml
models:
  Project:
    name: string
    code: string unique
    description: text nullable
    hasMany: FirmwareVersion

  FirmwareVersion:
    project_id: id foreign:projects
    version: string
    is_stable: boolean default:false
    changelog: text nullable
    file_path: string nullable
    released_at: nullable timestamp
    hasMany: Documentation, SupportRequest
    belongsTo: Project

  Documentation:
    firmware_version_id: id foreign:firmware_versions
    title: string
    file_path: string nullable
    description: text nullable
    belongsTo: FirmwareVersion

  SupportRequest:
    firmware_version_id: id foreign:firmware_versions
    created_by: id foreign:users
    assigned_to: id foreign:users nullable
    title: string
    status: enum:pending,accepted,denied,closed default:pending
    request_text: text
    steps_to_reproduce: text nullable

  UserProject:
    user_id: id foreign:users
    project_id: id foreign:projects
    timestamps: true

controllers:
  # Resource kontroleri ce se dodavati u narednoj fazi rucno.
```

**Komanda za generisanje:**
```bash
php artisan blueprint:build
```

## GitHub Actions CI

CI workflow je konfigurisan u `.github/workflows/ci.yml` i pokreće se na svaki push i pull request.

### Sadržaj CI workflow fajla:

```yaml
name: CI

on:
  push:
    branches: [ main, master ]
  pull_request:
    branches: [ main, master ]

jobs:
  tests-and-lint:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout koda
        uses: actions/checkout@v4

      - name: Postavi PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, dom, fileinfo, sqlite
          coverage: none

      - name: Keširaj Composer pakete
        uses: actions/cache@v4
        with:
          path: vendor
          key: ${{ runner.os }}-php-${{ hashFiles('composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-php-

      - name: Instaliraj PHP zavisnosti
        run: composer install --no-progress --prefer-dist --no-interaction

      - name: Pripremi .env i bazu
        run: |
          cp .env.example .env
          php artisan key:generate
          mkdir -p database
          touch database/database.sqlite
          php artisan migrate:fresh --seed

      - name: Pokreni testove
        run: php artisan test

      - name: Pokreni Pint (code style)
        run: php ./vendor/bin/pint --test
```

## Šta je generisano alatom, a šta ručno

### Generisano Blueprint-om:
- Modeli: `Project`, `FirmwareVersion`, `Documentation`, `SupportRequest`, `UserProject`
- Migracije: `create_projects_table`, `create_firmware_versions_table`, `create_documentations_table`, `create_support_requests_table`, `create_user_projects_table`
- Factory-ji: `ProjectFactory`, `FirmwareVersionFactory`, `DocumentationFactory`, `SupportRequestFactory`, `UserProjectFactory`

### Ručno pisano:
- **User model** - proširen sa ulogama i relacijama (Authenticatable)
- **Svi kontroleri** - `UserController`, `ProjectController`, `FirmwareVersionController`, `SupportRequestController`
- **FormRequest klase** - validacija za sve CRUD operacije
- **Policy klase** - `UserPolicy`, `ProjectPolicy`, `FirmwareVersionPolicy`, `SupportRequestPolicy`
- **Rute** - sve resource rute i use-case rute
- **Blade view-ovi** - svi CRUD view-ovi i forme
- **Seedere** - `UsersSeeder` sa srpskim korisnicima
- **Testovi** - feature testovi za use-case tokove
- **CI workflow** - GitHub Actions konfiguracija

### Generisano Laravel Breeze-om:
- Autentifikacioni kontroleri (`Auth/*`)
- Blade komponente i layout-ovi
- Auth rute

## Autorizacija i uloge

Sistem podržava tri uloge korisnika:

1. **Administrator** - pun pristup svim funkcionalnostima (CRUD korisnika, projekata, verzija, prijava)
2. **Inženjer** - može da dodaje verzije firmvera, uređuje prijave grešaka, dodeljuje prijave sebi
3. **Klijent** - može da pregleda projekte, preuzme firmver, prijavi problem

Autorizacija je implementirana kroz **Policy klase** i **middleware** provere.

## Pokretanje projekta

```bash
# Instalacija zavisnosti
composer install
npm install

# Podešavanje okruženja
cp .env.example .env
php artisan key:generate

# Migracije i seed
php artisan migrate:fresh --seed

# Pokretanje development servera
php artisan serve
npm run dev
```

## Testiranje

```bash
# Pokretanje svih testova
php artisan test

# Pokretanje specifičnih testova
php artisan test --filter SupportRequestTest
php artisan test --filter FirmwareVersionTest
```

## Code style (Pint)

```bash
# Automatsko formatiranje
composer pint

# Provera bez izmena
php ./vendor/bin/pint --test
```

## Screenshot-ovi aplikacije

Sledeći screenshot-ovi su dostupni u `/docs/screenshots/` direktorijumu:

1. `01-login.png` - Login ekran
2. `02-admin-users.png` - Admin panel - lista korisnika
3. `03-projects-list.png` - Lista projekata
4. `04-project-detail.png` - Detalj projekta sa verzijama
5. `05-firmware-version.png` - Detalj verzije firmvera
6. `06-support-request.png` - Prijava greške
7. `07-create-firmware.png` - Forma za dodavanje nove verzije

*Napomena: Screenshot-ovi će biti dodati nakon finalnog testiranja aplikacije.*

## Commit istorija

Projekat je razvijen iterativno sa sledećim commit-ovima:

1. `Inicijalizacija Laravel projekta i osnovna podešavanja`
2. `Autentifikacija i uloge korisnika (admin/inženjer/klijent)`
3. `Blueprint generisanje modela/migracija i inicijalni modeli domena`
4. `CRUD funkcionalnosti i resource kontroleri za glavne modele`
5. `Implementirani use-case tokovi: prijava greške, nova verzija, download firmvera`
6. `Feature testovi za ključne use-case tokove`
7. `Pint i GitHub Actions CI (testovi + lint)`

## Licenca

MIT License
