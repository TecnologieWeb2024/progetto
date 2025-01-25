<div class="container mb-4">
    <h1 class="mb-4">Contact Us</h1>

    <p>If you have any questions, feel free to reach out to us through the following methods:</p>

    <h2 class="mt-4">Email</h2>
    <p>You can email us at <a href="mailto:info@example.com">info@example.com</a></p>

    <h2 class="mt-4">Phone</h2>
    <p>Call us at: (123) 456-7890</p>

    <h2 class="mt-4">Address</h2>
    <p>Visit us at:</p>
    <address>
        123 Fake Street<br>
        Springfield, USA
    </address>

    <h2 class="mt-4">Contact Form</h2>
    <form action="submit_contact.php" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
            <div class="invalid-feedback">
                Please enter your name.
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
            <div class="invalid-feedback">
                Please enter a valid email address.
            </div>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
            <div class="invalid-feedback">
                Please enter your message.
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>