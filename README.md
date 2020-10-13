# quotes-api
A simple REST API built in Laravel with Passport authentication.

## Routes
| Method | Route | Parameters | Description | Authorized |
| ------ | ----- | --------------- | ----------- | --------- |
| GET | /api/v1/quote | {author, category} | Get all quotes | False |
| GET | /api/v1/quote/{id} | | Get single quote | False |
| GET | /api/v1/quote/random | {author, category} | Get random quote | False |
| POST | /api/v1/quote | author, quote, {category} | Create a quote | True |
| PUT/PATCH | /api/v1/quote/{id} | {author, quote, category} | Update a quote | True |
| DELETE | /api/v1/quote/{id} | | Delete a quote | True |
||
| GET | /user | | Get auth user | True |
| POST | /api/register | name, email, password, password_confirmation | Register a user | False |
| POST | /api/login | email, password | Login a user | False |
| POST | /api/logout | | Logout a user | True |

\* {...params} are optional parameters
