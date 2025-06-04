# Jobber API Integration Documentation

## Table of Contents
- [Authentication](#authentication)
- [Jobber Integration](#jobber-integration)
- [Authentication Requirements](#authentication-requirements)
- [Base URL](#base-url)
- [Response Format](#response-format)

## Authentication

### Login
- **Endpoint:** `POST /api/login`
- **Controller:** `AuthController@login`
- **Purpose:** Authenticate user and get access token
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

- All Jobber-related endpoints require authentication
- Routes are protected by `auth:sanctum` middleware
- A valid Bearer token must be included in the request header

## Base URL
All endpoints should be prefixed with `/api/`

## Response Format
The API follows RESTful conventions and returns JSON responses. Include the Bearer token in the Authorization header:
```
Authorization: Bearer <your_access_token>
``` 