# Role-Based User Management REST API - Postman Documentation

## Base URL
```
{{base_url}}
```

## Environment Variables
Set these in Postman:
- `base_url` - Your Laravel API URL (e.g., `http://localhost:8000/api`)
- `user_token` - Token for normal user (set after login)
- `admin_token` - Token for admin (set after admin login)
- `reset_token` - Token for password reset (set after OTP verification)

---

## Authentication Folder

### 1. Register
**POST** `/api/register`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password123",
    "password_confirmation": "Password123"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user",
            "created_at": "2026-11-25T10:00:00.000000Z"
        },
        "token": "1|abcd1234..."
    }
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

---

### 2. Login
**POST** `/api/login`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "email": "john@example.com",
    "password": "Password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user",
            "created_at": "2026-11-25T10:00:00.000000Z"
        },
        "token": "1|abcd1234..."
    }
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid credentials",
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

**Postman Test Script:**
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.environment.set("user_token", jsonData.data.token);
}
```

---

### 3. Logout
**POST** `/api/logout`

**Headers:**
```
Content-Type: application/json
Authorization: Bearer {{user_token}}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Unauthenticated",
    "errors": []
}
```

---

## User Profile Folder

### 4. Get Profile
**GET** `/api/profile`

**Headers:**
```
Authorization: Bearer {{user_token}}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Profile retrieved successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "http://localhost:8000/storage/avatars/avatar.jpg",
        "role": "user",
        "created_at": "2026-11-25T10:00:00.000000Z"
    }
}
```

---

### 5. Update Profile
**PUT** `/api/profile`

**Headers:**
```
Content-Type: application/json
Authorization: Bearer {{user_token}}
```

**Body (raw JSON):**
```json
{
    "name": "John Updated",
    "email": "johnupdated@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Updated",
        "email": "johnupdated@example.com",
        "avatar": null,
        "role": "user",
        "created_at": "2026-11-25T10:00:00.000000Z"
    }
}
```

---

### 6. Upload Avatar
**POST** `/api/profile/avatar`

**Headers:**
```
Authorization: Bearer {{user_token}}
```

**Body (form-data):**
- `avatar` (file) - Select an image file (JPEG, PNG, JPG, GIF, max 2MB)

**Success Response (200):**
```json
{
    "success": true,
    "message": "Avatar uploaded successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "http://localhost:8000/storage/avatars/xyz123.jpg",
        "role": "user",
        "created_at": "2026-11-25T10:00:00.000000Z"
    }
}
```

---

### 7. Delete Avatar
**DELETE** `/api/profile/avatar`

**Headers:**
```
Authorization: Bearer {{user_token}}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Avatar deleted successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": null,
        "role": "user",
        "created_at": "2026-11-25T10:00:00.000000Z"
    }
}
```

---

## Password Recovery Folder

### 8. Forgot Password (Request OTP)
**POST** `/api/forgot-password`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "email": "john@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "OTP sent to your email"
}
```

**Error Response (429):**
```json
{
    "success": false,
    "message": "Too many attempts. Please try again later.",
    "errors": []
}
```

---

### 9. Verify OTP
**POST** `/api/verify-otp`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "email": "john@example.com",
    "otp": "123456"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "OTP verified successfully",
    "data": {
        "reset_token": "abcd1234efgh5678..."
    }
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Invalid or expired OTP",
    "errors": {
        "otp": ["The OTP is invalid or has expired."]
    }
}
```

**Postman Test Script:**
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.environment.set("reset_token", jsonData.data.reset_token);
}
```

---

### 10. Reset Password
**POST** `/api/reset-password`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "email": "john@example.com",
    "reset_token": "{{reset_token}}",
    "password": "NewPassword123",
    "password_confirmation": "NewPassword123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Password reset successfully"
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Invalid or expired reset token",
    "errors": {
        "reset_token": ["The reset token is invalid or has expired."]
    }
}
```

---

## Admin Folder

### 11. Admin Login
**POST** `/api/login`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "email": "admin@example.com",
    "password": "Admin@123456"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": "admin",
            "created_at": "2026-11-25T10:00:00.000000Z"
        },
        "token": "1|xyz789..."
    }
}
```

**Postman Test Script:**
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    pm.environment.set("admin_token", jsonData.data.token);
}
```

---

### 12. Admin Dashboard
**GET** `/api/admin/dashboard`

**Headers:**
```
Authorization: Bearer {{admin_token}}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Dashboard stats retrieved successfully",
    "data": {
        "total_users": 10,
        "total_admins": 1,
        "total_normal_users": 9
    }
}
```

**Error Response (403):**
```json
{
    "success": false,
    "message": "Unauthorized",
    "errors": []
}
```

---

### 13. Get All Users
**GET** `/api/admin/users`

**Headers:**
```
Authorization: Bearer {{admin_token}}
```

**Query Params (optional):**
- `search` - Search by name or email
- `per_page` - Items per page (default: 10)

**Success Response (200):**
```json
{
    "success": true,
    "message": "Users retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 2,
                "name": "John Doe",
                "email": "john@example.com",
                "avatar": "http://localhost:8000/storage/avatars/xyz123.jpg",
                "created_at": "2026-11-25T10:00:00.000000Z"
            }
        ],
        "per_page": 10,
        "total": 9
    }
}
```

---

### 14. Get User By ID
**GET** `/api/admin/users/{id}`

**Headers:**
```
Authorization: Bearer {{admin_token}}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "User retrieved successfully",
    "data": {
        "id": 2,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "http://localhost:8000/storage/avatars/xyz123.jpg",
        "created_at": "2026-11-25T10:00:00.000000Z"
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "User not found",
    "errors": []
}
```

---

## HTTP Status Codes Reference

- **200** - Successful request
- **201** - Resource created
- **401** - Unauthenticated (missing or invalid token)
- **403** - Unauthorized (insufficient permissions)
- **404** - Resource not found
- **422** - Validation error
- **429** - Too many requests (rate limit exceeded)
- **500** - Unexpected server error

---

## Setup Instructions

### 1. Mailtrap Configuration
Get your Mailtrap credentials from https://mailtrap.io/
- Sign up for a free account
- Create a new inbox
- Copy the SMTP credentials
- Update your `.env` file:
```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

### 2. Admin Credentials
Default admin account:
- Email: `admin@example.com`
- Password: `Admin@123456`

### 3. Start Laravel Server
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000/api`

### 4. Import Collection to Postman
1. Copy this documentation
2. Create a new collection in Postman
3. Add each request according to the documentation
4. Set up environment variables
5. Use the test scripts to automatically set tokens

---

## Testing Workflow

1. **Register a new user** → Save user_token
2. **Login as admin** → Save admin_token
3. **Get/Update profile** (use user_token)
4. **Upload/Delete avatar** (use user_token)
5. **Test password recovery**:
   - Request OTP
   - Verify OTP → Save reset_token
   - Reset password
6. **Access admin dashboard** (use admin_token)
7. **View all users** (use admin_token)
8. **View specific user** (use admin_token)
