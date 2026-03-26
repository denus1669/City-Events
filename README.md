# 🏙️ WP City Events API
> REST API для управления городскими событиями в WordPress

<div align="center">

[![WordPress](https://img.shields.io/badge/WordPress-6.0+-21759B?style=for-the-badge&logo=wordpress)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php)](https://php.net/)
[![REST API](https://img.shields.io/badge/REST-API-FF6C37?style=for-the-badge&logo=postman)](https://restfulapi.net/)

</div>

---

## 🌐 Ссылки

| Ресурс | Ссылка |
|--------|-------|
| **Сайт** | [o90434uc.beget.tech](http://o90434uc.beget.tech/) |
| **Endpoint API** | `http://o90434uc.beget.tech/wp-json/events/v1/events` |
| **API Key** | `my-secret-key-123` *(передавать в заголовке `x-api-key`)* |

---

## ✨ Реализованные возможности

### 🎯 Основной функционал

| № | Фича | Описание |
|---|------|---------|
| 1 | **Custom Post Type** | Создан тип записи `city_event` с мета-полями (place, start_at, popularity и др.) |
| 2 | **REST API** | Полный CRUD (GET, POST, PUT, DELETE) с валидацией данных |
| 3 | **Cursor Pagination** | Навигация Next/Prev через SQL-фильтры |
| 4 | **Валидация** | Запрет на удаление прошедших событий, проверка статусов и расчет популярности |
| 5 | **Админка** | Кастомный Metabox с маской ввода даты и JS-валидацией |

---

## 📡 Примеры запросов

### 🔍 GET — Получить список событий

```http
GET http://o90434uc.beget.tech/wp-json/events/v1/events
{
    "data": [
        {
            "id": 76,
            "title": "Лекция по PHP",
            "place": "Офис Beget",
            "start_at": "2026-03-26 11:20",
            "status": "published",
            "popularity": 5
        },
        {
            "id": 78,
            "title": "Лекция по PHP",
            "place": "Офис Beget",
            "start_at": "2026-03-26 11:20",
            "status": "published",
            "popularity": 5
        }
    ],
    "meta": {
        "total": 13,
        "per_page": 10
    },
    "links": {
        "self": "http://o90434uc.beget.tech/wp-json/events/v1/events",
        "next": "http://o90434uc.beget.tech/wp-json/events/v1/events?cursor=eyJpZCI6OTIsInBvcCI6NSwic3RhcnQiOiIyMDI2LTA0LTE1IDIwOjAwIiwiZGlyIjoibmV4dCJ9",
        "prev": null
    }
}

POST http://o90434uc.beget.tech/wp-json/events/v1/events
Content-Type: application/json
x-api-key: my-secret-key-123
Тело запроса:
{
  "title": "Концерт джаза в парке",
  "place": "Центральный парк",
  "start_at": "2026-04-15 20:00",
  "end_at": "2026-04-15 21:00",
  "capacity": 3000,
  "tags": ["джаз", "open-air"],
  "status": "draft"
}

Ответ:
{
    "id": 96,
    "link": "http://o90434uc.beget.tech/city-events/концерт-джаза-в-парке-9/",
    "api_url": "http://o90434uc.beget.tech/wp-json/events/v1/events/96",
    "title": "Концерт джаза в парке",
    "place": "Центральный парк",
    "start_at": "2026-04-15 20:00",
    "end_at": "2026-04-15 21:00",
    "tags": ["джаз", "open-air"],
    "capacity": 3000,
    "status": "draft",
    "popularity": 5,
    "change_number": 1
}

PUT http://o90434uc.beget.tech/wp-json/events/v1/events/96
Content-Type: application/json
x-api-key: my-secret-key-123
Тело запроса:
{
  "capacity": 5000
}

Ответ:
{
    "id": 96,
    "title": "Концерт джаза в парке",
    "link": "http://o90434uc.beget.tech/city-events/концерт-джаза-в-парке-9/",
    "place": "Центральный парк",
    "start_at": "2026-04-15 20:00",
    "end_at": "2026-04-15 21:00",
    "tags": ["джаз", "open-air"],
    "capacity": 5000,
    "status": "draft",
    "popularity": 5,
    "change_number": 2
}

DELETE http://o90434uc.beget.tech/wp-json/events/v1/events/96
x-api-key: my-secret-key-123
Ответ:
{
    "message": "Событие успешно удалено"
}
