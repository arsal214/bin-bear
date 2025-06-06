# Jobber API Integration Documentation For BinBear

## Table of Contents
- [Login & Authentication](#login--authentication)
- [Jobber OAuth Flow](#jobber-oauth-flow)
- [API Endpoints](#api-endpoints)
  - [Create Client](#create-client)
  - [Get Client](#get-client)
  - [Get Available Times](#get-available-times)
- [Base URL](#base-url)
- [Response Format](#response-format)

---

## Login & Authentication

All endpoints require authentication using a Bearer token. You must log in to obtain this token.

### 1. Login
- **Endpoint:** `POST /api/login`
- **Purpose:** Authenticate a user and receive a Bearer token
- **Request Body:**
  ```json
  {
    "email": "user@example.com",
    "password": "your_password"
  }
  ```
- **Response:**
  ```json
  {
    "token": "<your_bearer_token>"
  }
  ```
- **Usage:** Include this token in the `Authorization` header for all subsequent requests:
  ```
  Authorization: Bearer <your_bearer_token>
  ```

---

## Jobber OAuth Flow

To use Jobber features, you must connect your Jobber account via OAuth. This is a one-time process per user.

### 2. Start Jobber OAuth
- **Endpoint:** `GET /api/jobber/auth`
- **Purpose:** Redirects you to Jobber to authorize the app
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response:**
  ```json
  {
    "auth_url": "https://api.getjobber.com/api/oauth/authorize?...",
    "message": "Visit this URL to authorize the app"
  }
  ```
- **Instructions:** Open the `auth_url` in your browser and complete the authorization.

### 3. Jobber OAuth Callback
- **Endpoint:** `GET /api/jobber/callback`
- **Purpose:** Handles the redirect from Jobber after authorization
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **What happens:**
  - The system exchanges the code for an access token and stores it for your user.
  - You are now ready to use Jobber-powered endpoints!

---

## API Endpoints

### Create Client
- **Endpoint:** `POST /api/create-client`
- **Purpose:** Create a new client in Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Request Body:**
  ```json
  {
    "first_name": "Test",
    "last_name": "Client",
    "email": "test.client@example.com",
    "phone": "123-456-7890"
  }
  ```
- **Response (Success):**
  ```json
  {
    "message": "Client created successfully in Jobber and saved to DB",
    "jobber_client_id": "...",
    "client_data": { ... }
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - You must have completed the Jobber OAuth flow.
  - The Bearer token must belong to a user who has authorized Jobber.

### Get Client
- **Endpoint:** `GET /api/clients/{jobberClientId}`
- **Purpose:** Retrieve client details from Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response (Success):**
  ```json
  {
    "message": "Client fetched successfully",
    "client": { ... }
  }
  ```
- **Response (Error):**
  ```json
  {
    "message": "Jobber not authorized for this user."
  }
  ```
- **Notes:**
  - The client ID must be a valid Jobber client ID.
  - The Bearer token must belong to a user who has authorized Jobber.

### Get Available Times
- **Endpoint:** `GET /api/get-available-times`
- **Purpose:** Get available booking times from Jobber
- **Headers:**
  - `Authorization: Bearer <your_bearer_token>`
- **Response (Success):**
  ```json
  {
    "message": "Scheduled jobs fetched successfully",
    "scheduled_jobs": [ ... ],
    "available_slots": [ ... ]
  }
  ```
- **Notes:**
  - The Bearer token must belong to a user who has authorized Jobber.
  - The available slots are calculated based on scheduled jobs in Jobber.

---

## Base URL
All endpoints are prefixed with `/api/`.

## Response Format
All responses are JSON. Always include your Bearer token in the `Authorization` header:
```
Authorization: Bearer <your_bearer_token>
```

---

## Quick Start Flow
1. **Login** to get your Bearer token.
2. **Authorize Jobber** via `/api/jobber/auth` and complete the OAuth flow.
3. Use your Bearer token to access Jobber-powered endpoints like creating clients, fetching clients, and getting available times. 