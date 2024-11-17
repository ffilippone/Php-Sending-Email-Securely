<h2>Prerequisites</h2>
Install <b>PHPMailer</b> via Composer:
<pre>composer require phpmailer/phpmailer</pre>
<h2>Explanation</h2>
<h3>Input Sanitization and Validation:</h3>
The sanitizeInput() function trims whitespace and escapes HTML special characters to prevent XSS.
Fields are validated for required data, and the email address is validated using filter_var().
<h3>Error Handling:</h3>
If any validation errors occur, they are returned as a JSON response for easy handling by the frontend.
<h3>Email Sending with PHPMailer:</h3>
PHPMailer ensures reliable and secure email sending, especially with SMTP support.
<h3>JSON Response:</h3>
The script returns JSON responses, making it suitable for use with AJAX requests.
