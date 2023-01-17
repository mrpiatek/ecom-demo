# Store API demo

# Core features
- CRUD operations on Product, Cart and LineItem (product in cart)
- Built on top of [API Platform](https://api-platform.com):
    - Hypermedia (JSON-LD and HAL)
    - Machine-readable documentation of the API in the Hydra and Swagger/Open API formats
    - Human-readable documentation built with Swagger UI (including a sandbox)
    - Pagination
    - Validation
    - Errors serialization
    - GraphQL support
    - Asynchronous persistence
- JWT Authentication
- Role based access control
- Tests (unit, functional and integration):
    - Optimized password hashing for test environment
- Development and test environment fixtures
- UUID support
- Project constraints:
    - Predefined pagination of 3 products per page
    - Maximum of 10 items of same product in cart
    - Maximum of 3 different products in cart

# Prerequsites
- docker
- docker compose

# Installation

- Run `docker compose up`
- Generate JWT keys
```
docker compose exec php sh -c '
    set -e
    apk add openssl
    php bin/console lexik:jwt:generate-keypair
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```

# Usage

The API has all endpoints documented at `https://localhost:1443/docs`.

## Login

User authentication can be done by calling `POST https://localhost:1443/auth` with:
```json
{
  "username": "username",
  "password": "password"
}
```

The API responds with the token. The token can be used to authenticate requests by adding the following header:
`Authorization: Bearer TOKEN`

Development image comes with the following users preconfigured:
| username | password | is admin?
| - | - | -
| admin | admin | yes
| user1 | user1 | no
| user2 | user2 | no

## Managing products
Only admins can manage products.
New product can be added by calling `POST https://localhost:1443/products` with:
```json
{
  "name": "Product name",
  "price": 100
}
```
> The prices are stored as integers and fractional part of the price is represented by the integer. Example $1.99 becomes 199 in the API.

## Managing carts
Only authenticated users can manage carts. Regular users can only view and edit carts they created. Admins can view all carts.

Current user's carts can be viewed by calling `GET https://localhost:1443/carts`. To view specific cart `GET https://localhost:1443/carts/ID` can be called.

## Addindg product to the cart
Authenticated users can add product to any of their carts by calling `POST https://localhost:1443/carts/line_items` with:
```json
{
  "cart": "carts/CART ID",
  "product": "products/PRODUCT ID",
  "quantity": 10
}
```

There can be no more than 3 unique products in the single cart, and no more than 10 same products

> `cart` and `product` fields use IRI identifiers, therefore they need full IRI instead of ID
