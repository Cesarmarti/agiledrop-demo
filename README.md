# Agiledrop task

Solution for the Agiledrop task. The solution uses SQLite as its database. Most routes are protected and require that a Bearer token is provided in the request header. It is also recommended that the `Accept` header is set to `application/json`. The User can retrieve their authentication token by first registering (using `/api/register`) and then logging-in (`/api/login`).

API endpoints are described in [API endpoints](##API-endpoionts) and as a OpenAPI specification [OpenAPI](./openapi.yaml).

## Setup

```
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan serve
```

## Demo

1. Make a `POST` request to `/api/register` and provide a `name`, `email` and `password` in the request body.
2. Login into the system with a `POST` request to `/api/login` by providing the `email` and `password` in the request body.
3. The server responds with the User's authentication token. Put that token (withouth the `DIGIT|` ) in the header &#8594; key: `Authorization`, value: `Bearer XXXX`
4. Now you can access all the other endpoints.
5. To upload a file, make a POST request to `/api/media-files` and in the request body provide the `file` and `description`. The `title` is optional, if none is provided, the title will be the base name of the uploaded file.
6. The server saves the file to the filesystem and makes a new record in the database. That record is then returned to the User.
7. The User can use the `id` in the returned record to download the file by making a `GET` request to `/api/media-files/{id}/download`.

## API endpoints

#### Authentication

<details>
 <summary><code>POST</code> <code><b>/api/register</b></code> <code>(Register a new User)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |

##### POST body

> | name              |  type     | data type      | description                         |
> |-------------------|-----------|----------------|-------------------------------------|
> | `name` |  required | string   | User's name        |
> | `email` |  required | string   | User's email        |
> | `password` |  required | string   | User's password        |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | 
> | `422`         | `application/json`        | Validation errors                                                          |


</details>

<details>
 <summary><code>POST</code> <code><b>/api/login</b></code> <code>(Login an exisisting User)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |

##### POST body

> | name              |  type     | data type      | description                         |
> |-------------------|-----------|----------------|-------------------------------------|
> | `email` |  required | string   | User's email        |
> | `password` |  required | string   | User's password        |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | 
> | `401`         | `application/json`        | Invalid credentials
> | `422`         | `application/json`        | Validation errors                                                          |                           |
</details>

<details>
 <summary><code>POST</code> <code><b>/api/logout</b></code> <code>(Logout the authenticated User)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |
> | `Authorization` | Bearer token        |

##### POST body

> | name              |  type     | data type      | description                         |
> |-------------------|-----------|----------------|-------------------------------------|


##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | 
> | `401`         | `application/json`        | Unauthenticated

</details>

------------------------------------------------------------------------------------------


#### Media files

<details>
 <summary><code>GET</code> <code><b>/api/media-files</b></code> <code>(Fetch all File infos)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |
> | `Authorization` | Bearer token        |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `[{...},{...},...]`|
> | `401`         | `application/json`        | Unauthenticated |


</details>

<details>
 <summary><code>POST</code> <code><b>/api/media-files</b></code> <code>(Upload a File)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |
> | `Authorization` | Bearer token        |

##### POST body

> | name              |  type     | data type      | description                         |
> |-------------------|-----------|----------------|-------------------------------------|
> |`file`| required | type | |
> |`title`| optional | string | |
> |`description`| required | string | |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `201`         | `application/json`        | `[{...},{...},...]`|
> | `401`         | `application/json`        | Unauthenticated |
> | `422`         | `application/json`        | Validation errors                                                          |                           |

</details>

<details>
 <summary><code>GET</code> <code><b>/api/media-files/{id}</b></code> <code>(Get specific File info)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |
> | `Authorization` | Bearer token        |

##### Parameters

> | name      |  type     | data type               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | `id`      |  required | string   | ID of file  |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | `{...}`|
> | `401`         | `application/json`        | Unauthenticated |
> | `404`         | `application/json`        | Missing file |


</details>

<details>
 <summary><code>GET</code> <code><b>/api/media-files/{id}/download</b></code> <code>(Download specific File)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |
> | `Authorization` | Bearer token        |

##### Parameters

> | name      |  type     | data type               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | `id`      |  required | string   | ID of file  |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `200`         | `application/json`        | File|
> | `401`         | `application/json`        | Unauthenticated |
> | `404`         | `application/json`        | Missing file |


</details>

<details>
 <summary><code>DELETE</code> <code><b>/api/media-files/{id}</b></code> <code>(Delete a File)</code></summary>

##### Headers

> | name              |    description                         |
> |-------------------|-------------------------------------|
> | `Accept` |   `application/json`        |
> | `Authorization` | Bearer token        |

##### Parameters

> | name      |  type     | data type               | description                                                           |
> |-----------|-----------|-------------------------|-----------------------------------------------------------------------|
> | `id`      |  required | string   | ID of file  |

##### Responses

> | http code     | content-type                      | response                                                            |
> |---------------|-----------------------------------|---------------------------------------------------------------------|
> | `204`         | `application/json`        | |
> | `401`         | `application/json`        | Unauthenticated |
> | `404`         | `application/json`        | Missing file |


</details>
