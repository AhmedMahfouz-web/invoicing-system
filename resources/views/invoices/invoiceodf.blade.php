<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Eminent Studio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 2rem;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .invoice {
            background-color: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 3rem;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            top: 0;
            right: -3rem;
            width: 4px;
            height: 100%;
            background-color: #2563eb;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo {
            color: #2563eb;
            width: 48px;
            height: 48px;
        }

        .company-name {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: -0.5px;
        }

        .studio-text {
            font-size: 1.125rem;
            color: #4b5563;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: auto auto;
            gap: 0.75rem 2rem;
            text-align: right;
            padding-right: 2rem;
        }

        .invoice-details dt {
            font-weight: 600;
            color: #374151;
        }

        .invoice-details dd {
            color: #111827;
        }

        .invoice-title {
            font-size: 2rem;
            color: #111827;
            margin-bottom: 2rem;
            font-weight: 600;
            text-align: center;
        }

        .client-details {
            margin-bottom: 3rem;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 6px;
        }

        .client-details h2 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #374151;
        }

        .client-details p {
            margin-bottom: 0.75rem;
            color: #4b5563;
        }

        .client-details span {
            font-weight: 500;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        th {
            background-color: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #2563eb;
        }

        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .invoice-summary {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .notes {
            flex: 1;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .notes p {
            margin-bottom: 0.5rem;
        }

        .totals {
            width: 300px;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 6px;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            color: #4b5563;
        }

        .total {
            font-weight: bold;
            border-top: 2px solid #e5e7eb;
            padding-top: 1rem !important;
            margin-top: 0.5rem;
            color: #111827 !important;
        }

        .payment-info {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .payment-info h3 {
            color: #374151;
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }

        .payment-info p {
            margin-bottom: 0.5rem;
            color: #4b5563;
            line-height: 1.5;
        }

        .signature {
            margin-top: 4rem;
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 0.5rem;
            color: #374151;
        }

        .kindly-note {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 1rem;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <div class="header">
            <div class="logo-container">
                <svg class="logo" viewBox="0 0 40 40" fill="none">
                    <path d="M20 0L40 20L20 40L0 20L20 0Z" fill="currentColor" />
                    <path d="M20 8L32 20L20 32L8 20L20 8Z" fill="white" />
                </svg>
                <div>
                    <div class="company-name">eminent</div>
                    <div class="studio-text">Studio</div>
                </div>
            </div>
            <dl class="invoice-details">
                <dt>Invoice Number</dt>
                <dd>000001</dd>
                <dt>Payment method</dt>
                <dd>Western Union</dd>
                <dt>Amount due</dt>
                <dd>$440</dd>
                <dt>Date</dt>
                <dd>01-12-2023</dd>
            </dl>
        </div>

        <h1 class="invoice-title">Invoice</h1>

        <div class="client-details">
            <h2>Client Details:</h2>
            <p><span>Name: </span>Mostafa Mohamed Al Ansary</p>
            <p><span>Company: </span>Eminent Studio</p>
            <p><span>Mobile: </span>+201006104925</p>
            <p><span>Country: </span>Riyadh, Saudi Arabia</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Unit cost</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Brand Identity</td>
                    <td>500$</td>
                    <td>1</td>
                    <td>500$</td>
                </tr>
                <tr>
                    <td>Printings</td>
                    <td>200$</td>
                    <td>5</td>
                    <td>200$</td>
                </tr>
                <tr>
                    <td>Social Media</td>
                    <td>220$</td>
                    <td>2</td>
                    <td>40$</td>
                </tr>
                <tr>
                    <td>Website</td>
                    <td>50,000$</td>
                    <td>2</td>
                    <td>40$</td>
                </tr>
                <tr>
                    <td>Photography</td>
                    <td>20$</td>
                    <td>2</td>
                    <td>40$</td>
                </tr>
                <tr>
                    <td>3D Booth</td>
                    <td>10$</td>
                    <td>2</td>
                    <td>40$</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-summary">
            <div class="notes">
                <p>Kindly Note:</p>
                <p>* That all prices based on the project, this is not a pricelist</p>
                <p>* That all editing capabilities are up to USD Currency</p>
                <p>** You have the guarantee that this work will be available with lifetime access request.</p>
            </div>
            <div class="totals">
                <div>
                    <span>Subtotal</span>
                    <span>$400</span>
                </div>
                <div>
                    <span>Taxes (10%)</span>
                    <span>0$</span>
                </div>
                <div>
                    <span>Discount</span>
                    <span>0$</span>
                </div>
                <div class="total">
                    <span>Total</span>
                    <span>$440</span>
                </div>
            </div>
        </div>

        <div class="payment-info">
            <h3>Payment method:</h3>
            <p>Western Union</p>
            <p><strong>Receiver Information:</strong></p>
            <p>Name: Ehab Mohamed Osman Abdelhady</p>
            <p>Phone: +01006104925</p>
            <p>Address: Hassan Darwish, Dokki, Giza, Egypt</p>
            <p>Mobile: +201006104453</p>
            <p class="kindly-note">*Kindly note that we receive money in Dollar currency</p>
        </div>

        <div class="signature">
            Signature
        </div>
    </div>
</body>

</html>
