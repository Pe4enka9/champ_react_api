# Чемпионат 2026 - API для модуля В

## Документация

* Попытка доступа без авторизации (401 Unauthorized):

```json
[]
```

* Ресурс не существует (404 Not Found):

```json
[]
```

* Попытка нарушения уникальности (409 Conflict):

```json
[]
```

* Попытка доступа к запрещенному ресурсу (403 Forbidden):

```json
[]
```

* Ошибки валидации (422 Unprocessable Entity):

```json
{
    "message": "Invalid fields",
    "errors": {
        "name": [
            "The email field is required."
        ]
    }
}
```

### Регистрация

#### Запрос

* Метод: **POST**
* Путь: **/register**
* Заголовки:
    * Content-Type: application/json
* Тело:

```json
{
    "email": "test@mail.ru",
    "name": "Test",
    "password": "password1_"
}
```

Все поля обязательны.

* email - валидный, уникальный.
* имя - только латиница.
* пароль - минимум 8 символов, должен содержать хотя бы одну цифру и один спецсимвол.

#### Ответ

* Успешный ответ (201 Created):

```json
{
    "success": true
}
```

### Авторизация

#### Запрос

* Метод: **POST**
* Путь: **/login**
* Заголовки:
    * Content-Type: application/json
* Тело:

```json
{
    "email": "test@mail.ru",
    "password": "password1_"
}
```

Все поля обязательны.

#### Ответ

* Успешный ответ (200 OK):

```json
{
    "token": "1|WbNEumC0IFtou8tqYdtQ1QxhSdOqjGBASArpzqWOe2a38037"
}
```

* Некорректные данные (401 Unauthorized):

```json
[]
```

### Получение публичных досок

#### Запрос

* Метод: **GET**
* Путь: **/boards**

#### Ответ

* Успешный ответ (200 OK):

```json
[
    {
        "id": 1,
        "name": "Board 1",
        "owner": {
            "id": 1,
            "email": "test@mail.ru",
            "name": "Test"
        },
        "hash": "GcLP0QucOebqJRenfEn5f8JmFSyAGrrL",
        "is_public": true,
        "width": 1600,
        "height": 900,
        "likes": 1,
        "objects": [
            {
                "id": "d26b8959-8d1c-4df4-a9e3-f7451858fbbe",
                "type": "image",
                "x": 20,
                "y": 20,
                "width": 100,
                "height": 100,
                "rotation": 10,
                "focused_by": {
                    "id": 3,
                    "email": "test@mail.ru",
                    "name": "Test"
                }
            },
            {
                "id": "b328e3a6-fb74-441e-af56-d88697382ad0",
                "type": "image",
                "x": 20,
                "y": 20,
                "width": 100,
                "height": 100,
                "rotation": 10,
                "focused_by": null
            }
        ]
    }
]
```

### Получение доски по hash

#### Запрос

* Метод: **GET**
* Путь: **/board/{hash}**

#### Ответ

* Успешный ответ (200 OK):

```json
{
    "id": 1,
    "name": "Board 1",
    "owner": {
        "id": 1,
        "email": "test@mail.ru",
        "name": "Test"
    },
    "hash": "GcLP0QucOebqJRenfEn5f8JmFSyAGrrL",
    "is_public": true,
    "width": 1600,
    "height": 900,
    "likes": 1,
    "objects": [
        {
            "id": "d26b8959-8d1c-4df4-a9e3-f7451858fbbe",
            "type": "image",
            "x": 20,
            "y": 20,
            "width": 100,
            "height": 100,
            "rotation": 10,
            "focused_by": {
                "id": 3,
                "email": "test@mail.ru",
                "name": "Test"
            }
        },
        {
            "id": "b328e3a6-fb74-441e-af56-d88697382ad0",
            "type": "image",
            "x": 20,
            "y": 20,
            "width": 100,
            "height": 100,
            "rotation": 10,
            "focused_by": null
        }
    ]
}
```

### Доски пользователя (с правом редактирования)

#### Запрос

* Метод: **GET**
* Путь: **/users/boards**
* Заголовки:
    * Authorization: Bearer {token}

#### Ответ

* Успешный ответ (200 OK):

```json
[
    {
        "id": 1,
        "name": "Board 1",
        "owner": {
            "id": 1,
            "email": "test@mail.ru",
            "name": "Test"
        },
        "hash": "GcLP0QucOebqJRenfEn5f8JmFSyAGrrL",
        "is_public": true,
        "width": 1600,
        "height": 900,
        "likes": 1,
        "objects": [
            {
                "id": "d26b8959-8d1c-4df4-a9e3-f7451858fbbe",
                "type": "image",
                "x": 20,
                "y": 20,
                "width": 100,
                "height": 100,
                "rotation": 10,
                "focused_by": {
                    "id": 3,
                    "email": "test@mail.ru",
                    "name": "Test"
                }
            },
            {
                "id": "b328e3a6-fb74-441e-af56-d88697382ad0",
                "type": "image",
                "x": 20,
                "y": 20,
                "width": 100,
                "height": 100,
                "rotation": 10,
                "focused_by": null
            }
        ]
    }
]
```

### Создание доски

#### Запрос

* Метод: **POST**
* Путь: **/boards**
* Заголовки:
    * Content-Type: application/json
    * Authorization: Bearer {token}
* Тело:

```json
{
    "name": "Board 1"
}
```

* имя - обязательное, максимум 255 символов.

#### Ответ

* Успешный ответ (201 Created):

```json
{
    "success": true
}
```

### Доступ к доске

#### Запрос

* Метод: **GET**
* Путь: **/boards/{board_id}**
* Заголовки:
    * Authorization: Bearer {token}

#### Ответ

* Успешный ответ (200 OK):

```json
{
    "id": 1,
    "name": "Board 1",
    "owner": {
        "id": 1,
        "email": "test@mail.ru",
        "name": "Test"
    },
    "hash": "GcLP0QucOebqJRenfEn5f8JmFSyAGrrL",
    "is_public": true,
    "width": 1600,
    "height": 900,
    "likes": 1,
    "objects": [
        {
            "id": "d26b8959-8d1c-4df4-a9e3-f7451858fbbe",
            "type": "image",
            "x": 20,
            "y": 20,
            "width": 100,
            "height": 100,
            "rotation": 10,
            "focused_by": {
                "id": 3,
                "email": "test@mail.ru",
                "name": "Test"
            }
        },
        {
            "id": "b328e3a6-fb74-441e-af56-d88697382ad0",
            "type": "image",
            "x": 20,
            "y": 20,
            "width": 100,
            "height": 100,
            "rotation": 10,
            "focused_by": null
        }
    ]
}
```

### Предоставить доступ к доске пользователю по email

#### Запрос

* Метод: **POST**
* Путь: **/boards/{board_id}/access**
* Заголовки:
    * Content-Type: application/json
    * Authorization: Bearer {token}
* Тело:

```json
{
    "email": "test@mail.ru"
}
```

#### Ответ

* Успешный ответ (201 Created):

```json
{
    "success": true
}
```

### Сделать доску публичной

#### Запрос

* Метод: **POST**
* Путь: **/boards/{board_id}/make-public**
* Заголовки:
    * Authorization: Bearer {token}

#### Ответ

* Успешный ответ (200 OK):

```json
{
    "hash": "mAxZNLT6fYr4x2xVPNLjKM4dAQ2eCbe5"
}
```

### Сделать доску приватной

#### Запрос

* Метод: **POST**
* Путь: **/boards/{board_id}/make-private**
* Заголовки:
    * Authorization: Bearer {token}

#### Ответ

* Успешный ответ (200 OK):

```json
{
    "success": true
}
```

### Поставить лайк доске

#### Запрос

* Метод: **POST**
* Путь: **/boards/{board_id}/like**
* Заголовки:
    * Authorization: Bearer {token}

#### Ответ

* Успешный ответ (200 OK):

```json
{
    "success": true
}
```

### Обновление объектов доски

#### Запрос

* Метод: **PATCH**
* Путь: **/boards/{board_id}**
* Заголовки:
    * Content-Type: application/json
    * Authorization: Bearer {token}
* Тело:

```json
{
    "id": "d26b8959-8d1c-4df4-a9e3-f7451858fbbe",
    "type": "image",
    "x": 20,
    "y": 20,
    "rotation": 10,
    "focused_by": 3,
    "deleted": true
}
```

* id - необязательное (для изменения объекта).
* type - обязательное, должно соответствовать разрешенным типам (text, image, rectangle, circle, line).
* x - обязательное, целое значение от 0 до 1600.
* y - обязательное, целое значение от 0 до 900.
* width - необязательное, целое значение от 0 до 1600.
* height - необязательное, целое значение от 0 до 900.
* rotation - необязательное, число значение от -360 до 360.
* focused_by - необязательное, id пользователя.
* deleted - необязательное (для удаления объекта).

#### Ответ

* Успешный ответ (200 OK):

```json
[
    {
        "id": 1,
        "name": "Board 1",
        "owner": {
            "id": 1,
            "email": "test@mail.ru",
            "name": "Test"
        },
        "hash": "GcLP0QucOebqJRenfEn5f8JmFSyAGrrL",
        "is_public": true,
        "width": 1600,
        "height": 900,
        "likes": 1,
        "objects": [
            {
                "id": "d26b8959-8d1c-4df4-a9e3-f7451858fbbe",
                "type": "image",
                "x": 20,
                "y": 20,
                "width": 100,
                "height": 100,
                "rotation": 10,
                "focused_by": {
                    "id": 3,
                    "email": "test@mail.ru",
                    "name": "Test"
                }
            },
            {
                "id": "b328e3a6-fb74-441e-af56-d88697382ad0",
                "type": "image",
                "x": 20,
                "y": 20,
                "width": 100,
                "height": 100,
                "rotation": 10,
                "focused_by": null
            }
        ]
    }
]
```
