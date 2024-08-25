# Order Project

Proje, Laravel Framework 11.20.0 kullanarak geliştirilmiş bir RESTful API'dir. Proje, ürün yönetimi, sipariş yönetimi ve kampanya uygulamalarını içerir. Redis cache sistemi entegre edilmiştir.

## Gereksinimler

- PHP 8.2.12
- Laravel 11.20.0
- Composer
- Redis Server (3.0.504)
- MySQL
- Git

## Kurulum

### 1. Depoyu Klonlayın

```bash
git clone https://github.com/ahmetcandericioglu/OrderProject.git
cd OrderProject
```

### 2. Gerekli Paketleri Yükleyin
Composer ile gerekli PHP paketlerini yükleyin:

```bash
composer install
```

### 3. Ortam Dosyasını Ayarlayın

```bash
cp .env.example .env
```

Daha sonra .env dosyasını açın ve veritabanı, cache, Redis vb. ayarlarını yapın:

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000

...

CACHE_STORE=redis
CACHE_PREFIX=

...

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 4. Veritabanını Kurun

```bash
php artisan migrate
php artisan db:seed
```
* Seed'den gelen User bilgileri:
- Email: admin@gmail.com
- Password: 123456

### 5. Redis'i Çalıştırın

Windows kullanıcıları için:

* Redis'i indirip kurduktan sonra redis-server.exe dosyasını çalıştırın.
* Redis'in çalıştığından emin olmak için Redis CLI'ya redis-cli ile bağlanın.

### 6. Uygulama Sunucusunu Başlatın

```bash
php artisan serve
```

## Kullanım

### API CRUD İşlemleri

#### Kullanıcılar
* Register: POST /api/register
* Login: POST /api/login
* Siparişleri Getirme: GET /api/users/my-orders
* Logout: POST /api/users/logout

#### Ürünler
* Tüm Ürünleri Getirme: GET /api/products
* Ürün Ekleme: POST /api/products
* Ürün Güncelleme: PUT /api/products/{id}
* Ürün Silme: DELETE /api/products/{id}
* Ürün Getirme: GET /api/products/{id}
* Stok arttırma: POST /api/products/{id}/increase-stock
* Stok azaltma: POST /api/products/{id}/decrease-stock

#### Siparişler
* Kampanyalar dahil Sipariş Oluşturma: POST /api/orders/process
JSON input:

{
    "order_details": [
        {
            "product_id" : 0,
            "quantity": 0
        },
        {
            "product_id" : 0,
            "quantity": 0
        }
        .
        .
        .
    ]
}

* Tüm Siparişleri Getirme: GET /api/orders
* Sipariş Ekleme: POST /api/orders
* Sipariş Güncelleme: PUT /api/orders/{id}
* Sipariş Silme: DELETE /api/orders/{id}
* Sipariş Getirme: GET /api/orders/{id}
* Sipariş Detayları Getirme: GET /api/order-details/{id}

#### Kampanyalar
* Tüm Kampanyaları Getirme: GET /api/campaigns
* Kampanya Ekleme: POST /api/campaigns
* Kampanya Güncelleme: PUT /api/campaigns/{id}
* Kampanya Silme: DELETE /api/campaigns/{id}
* Kampanya Getirme: GET /api/campaigns/{id}

## Geliştirme Notları

* İleride yeni kampanyalar oluşturulabileceği için kampanya servisi strateji paternine uygun tasarlanmıştır yeni bir kampanya oluşturulduğunda bu kampanyanın class'ının yazılması ve database'e kaydedilmesi gerekmektedir. Daha sonrasında kampanyanın "type" özelliği kampanya servisinin "strategies" array'ine tanımlanmalıdır
* SOLID Prensipleri: Proje SOLID prensiplerine uygun şekilde geliştirilmiştir. İşlemler servis katmanında (Services) tanımlanmış olup, kontrol katmanından ayrılmıştır.
* Validasyon: Validasyon işlemleri servis katmanında gerçekleştirilmiştir.
* Error Handling: Exception handling try-catch blokları ile yapılmıştır, servislerde doğru hatalar fırlatılıp controller kısmında kontrol edilip gerekli durumlarda özel hata mesajları dönülmüştür.
* Redis ile cache işlemleri yapılmıştır 