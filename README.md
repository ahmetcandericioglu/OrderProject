# Order Project

Bu proje, Laravel Framework 11.20.0 kullanarak geliştirilmiş bir RESTful API'dir. Proje, ürün yönetimi, sipariş yönetimi ve kampanya uygulamalarını içerir. Redis cache sistemi entegre edilmiştir ve Memcached/Redis gibi teknolojiler kullanılarak optimize edilmiştir.

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

### 5. Redis'i Çalıştırın

Windows kullanıcıları için:

1- Redis'i indirip kurduktan sonra redis-server.exe dosyasını çalıştırın.
2- Redis'in çalıştığından emin olmak için Redis CLI'ya redis-cli ile bağlanın.

### 6. Uygulama Sunucusunu Başlatın

```bash
php artisan serve
```

## Kullanım

### API CRUD İşlemleri

#### Ürünler
* Ürün Ekleme: POST /api/products
* Ürün Güncelleme: PUT /api/products/{id}
* Ürün Silme: DELETE /api/products/{id}
* Ürün Getirme: GET /api/products/{id}
* Stok arttırma: POST /api/products/{id}/increase-stock
* Stok azaltma: POST /api/products/{id}/decrease-stock

#### Siparişler
* Sipariş Oluşturma: POST /api/orders/process

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
* Sipariş Getirme: GET /api/orders/{id}
* Sipariş Detayları Getirme: GET /api/order-details/{id}

## Geliştirme Notları

* SOLID Prensipleri: Proje SOLID prensiplerine uygun şekilde geliştirilmiştir. İşlemler servis katmanında (Services) tanımlanmış olup, kontrol katmanından ayrılmıştır.
* Validasyon: Validasyon işlemleri kontrol katmanında gerçekleştirilmiştir.
* Error Handling: Exception handling try-catch blokları ile yapılmıştır ve gerekli durumlarda özel hata mesajları dönülmüştür.