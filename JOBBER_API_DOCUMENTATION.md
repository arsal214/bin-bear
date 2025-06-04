# API Documentation

## Table of Contents
- [Authentication](#authentication)
- [Blogs](#blogs)
- [Coupons](#coupons)
- [Zip Codes](#zip-codes)
- [Bookings](#bookings)
- [Categories](#categories)
- [Payment (Stripe)](#payment-stripe)
- [Jobber Integration](#jobber-integration)

## Authentication

### Login
- **Endpoint:** `POST /api/login`
- **Controller:** `AuthController@login`
- **Purpose:** Authenticate user and get access token
- **Authentication:** Not required

## Blogs

### List/Create Blogs
- **Endpoint:** `GET/POST /api/blogs`
- **Controller:** `BlogController`
- **Purpose:** Get all blogs or create a new blog
- **Authentication:** Not required

### Get Popular Blogs
- **Endpoint:** `GET /api/blog/is-popular`
- **Controller:** `BlogController@isPopular`
- **Purpose:** Get list of popular blogs
- **Authentication:** Not required

## Coupons

### Manage Coupons
- **Endpoint:** `GET/POST/PUT/DELETE /api/coupons`
- **Controller:** `CouponController`
- **Purpose:** CRUD operations for coupons
- **Authentication:** Not required

## Zip Codes

### Manage Zip Codes
- **Endpoint:** `GET/POST/PUT/DELETE /api/zip-codes`
- **Controller:** `ZipCodeController`
- **Purpose:** CRUD operations for zip codes
- **Authentication:** Not required

## Bookings

### Manage Bookings
- **Endpoint:** `GET/POST/PUT/DELETE /api/bookings`
- **Controller:** `BookingController`
- **Purpose:** CRUD operations for bookings
- **Authentication:** Not required

### Get Price
- **Endpoint:** `GET /api/getPrice`
- **Controller:** `BookingController@getPrice`
- **Purpose:** Calculate booking price
- **Authentication:** Not required

## Categories

### Manage Categories
- **Endpoint:** `GET/POST/PUT/DELETE /api/categories`
- **Controller:** `CategoryController`
- **Purpose:** CRUD operations for categories
- **Authentication:** Not required

### Get Service Category
- **Endpoint:** `GET /api/category-page/{id}`
- **Controller:** `CategoryController@serviceCategory`
- **Purpose:** Get category details by ID
- **Authentication:** Not required

### Get All Categories
- **Endpoint:** `GET /api/allCategories`
- **Controller:** `CategoryController@allCategories`
- **Purpose:** Get list of all categories
- **Authentication:** Not required

### Get All List
- **Endpoint:** `GET /api/allList`
- **Controller:** `CategoryController@allList`
- **Purpose:** Get complete category list
- **Authentication:** Not required

### Get Subcategories
- **Endpoint:** `GET /api/subCategoryByID/{id}`
- **Controller:** `CategoryController@subCategory`
- **Purpose:** Get subcategories by category ID
- **Authentication:** Not required

### Get Popular Categories
- **Endpoint:** `GET /api/category/is-popular`
- **Controller:** `CategoryController@isPopular`
- **Purpose:** Get list of popular categories
- **Authentication:** Not required

## Payment (Stripe)

### Generate Payment Key
- **Endpoint:** `POST /api/payment-key-generate`
- **Controller:** `StripeController@paymentKey`
- **Purpose:** Generate payment key for Stripe
- **Authentication:** Not required

### Process Payment
- **Endpoint:** `POST /api/process-payment`
- **Controller:** `StripeController@processStripePayment`
- **Purpose:** Process Stripe payment
- **Authentication:** Not required

## Jobber Integration

*Note: All Jobber-related endpoints require authentication (Bearer token)*

### Get User Info
- **Endpoint:** `GET /api/user`
- **Purpose:** Get authenticated user information
- **Authentication:** Required (Bearer token)

### Jobber OAuth
- **Endpoint:** `GET /api/jobber/auth`
- **Controller:** `JobberOAuthController@redirectToJobber`
- **Purpose:** Redirect to Jobber OAuth
- **Authentication:** Required (Bearer token)

### Jobber Callback
- **Endpoint:** `GET /api/jobber/callback`
- **Controller:** `JobberOAuthController@handleCallback`
- **Purpose:** Handle Jobber OAuth callback
- **Authentication:** Required (Bearer token)

### Refresh Jobber Token
- **Endpoint:** `POST /api/jobber/refresh`
- **Controller:** `JobberOAuthController@refreshToken`
- **Purpose:** Refresh Jobber access token
- **Authentication:** Required (Bearer token)

### Create Jobber Client
- **Endpoint:** `POST /api/create-client`
- **Controller:** `JobberController@createClient`
- **Purpose:** Create a new client in Jobber
- **Authentication:** Required (Bearer token)

### Get Jobber Client
- **Endpoint:** `GET /api/clients/{jobberClientId}`
- **Controller:** `JobberController@getClient`
- **Purpose:** Get client details from Jobber
- **Authentication:** Required (Bearer token)

### Get Available Times
- **Endpoint:** `GET /api/get-available-times`
- **Controller:** `JobberController@getAvailableTimes`
- **Purpose:** Get available booking times
- **Authentication:** Required (Bearer token)

## Authentication Requirements

- Most routes are publicly accessible
- Routes under the `auth:sanctum` middleware require a valid Bearer token
- Protected routes include:
  - User information
  - All Jobber-related endpoints
  - Client management endpoints

## Base URL
All endpoints should be prefixed with `/api/`

## Response Format
The API follows RESTful conventions and typically returns JSON responses. For protected routes, include the Bearer token in the Authorization header:
```
Authorization: Bearer <your_access_token>
``` 