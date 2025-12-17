# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer Bearer {YOUR_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Anda bisa mendapatkan token dengan login melalui endpoint <code>POST /api/v1/login</code> dan gunakan token yang dikembalikan di header <code>Authorization: Bearer {token}</code>.
