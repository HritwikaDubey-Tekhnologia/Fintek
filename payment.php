<div class="payment-container">
    <div class="payment-form">
        <div class="form-header">
            <h2>Payment Form</h2>
        </div>

        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" placeholder="Enter amount" required>
        </div>

        <div class="payment-options">
            <label>
                <input type="radio" name="payment_option" value="upi"> UPI
            </label>
            <label>
                <input type="radio" name="payment_option" value="bank"> Bank
            </label>
            <!-- Add more payment options as needed -->
        </div>

        <div class="form-group">
            <button class="submit-btn" onclick="submitForm()">Continue</button>
        </div>
    </div>
</div>

<style>
    .payment-container {
        text-align: center; /* Adjust alignment as needed */
    }

    .payment-form {
        max-width: 400px;
        width: 100%;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: inline-block; /* Ensures it doesn't take full width */
        margin-top: 20px; /* Adjust margin as needed */
    }

    .form-header {
        background-color: #2196F3;
        color: white;
        text-align: center;
        padding: 20px;
    }

    .form-group {
        padding: 20px;
        box-sizing: border-box;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 15px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #2196F3;
    }

    .payment-options {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .payment-options label {
        display: flex;
        align-items: center;
        color: #555;
        margin-bottom: 10px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .payment-options input {
        margin-right: 10px;
    }

    .payment-options label:hover {
        color: #2196F3;
    }

    .submit-btn {
        background-color: #2196F3;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #1769aa;
    }
</style>

<script>
    function submitForm() {
        // Add your form submission logic here
        alert('Form submitted!');
    }
</script>
