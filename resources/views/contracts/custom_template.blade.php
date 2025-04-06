<!DOCTYPE html>
<html>

<head>
    <title>{{ $companyName }} Bus Service Vendor Contract</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        h1 {
            text-align: center;
            font-size: 16pt;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 20px;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 45%;
        }

        .underline {
            border-bottom: 1px solid black;
            min-width: 250px;
            display: inline-block;
        }

        .footer {
            font-size: 10pt;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h1>{{ $companyName }}<br>BUS SERVICE VENDOR CONTRACT</h1>

    <p>This Bus Service Vendor Contract ("Contract") is made effective as of <strong>{{ $date }}</strong>
        ("Effective Date") by and between:</p>

    <p><strong>{{ $companyName }}</strong>, a {{ $legalEntity }} registered under the laws of {{ $jurisdiction }},
        with its principal place of business at {{ $companyAddress }} ("Company"),<br>
        and<br>
        <strong>{{ $vendorName }}</strong>, an individual/business entity located at {{ $vendorAddress }} ("Vendor").
    </p>

    <div class="section">
        <strong>1. SCOPE OF SERVICES</strong><br>
        The Vendor shall provide the following bus transportation services ("Services") to the Company:<br>
        - Service Description: {{ $serviceDescription }}<br>
        - Service Area: {{ $serviceArea }}<br>
        - Schedule: {{ $schedule }}<br>
        - Vehicles: {{ $vehicles }}<br>
        - Contract Term: {{ $startDate }} to {{ $endDate }}
    </div>

    <div class="section">
        <strong>2. PAYMENT TERMS</strong><br>
        - Rate: {{ $rate }}<br>
        - Invoice Schedule: {{ $invoiceSchedule }}<br>
        - Payment Due: {{ $paymentDue }}<br>
        - Payment Method: {{ $paymentMethod }}
    </div>

    <div class="section">
        <strong>3. VENDOR RESPONSIBILITIES</strong><br>
        - Provide buses that meet safety and regulatory standards (e.g., Land Transportation Office compliance).<br>
        - Employ licensed, trained drivers.<br>
        - Maintain vehicles in good working order, with regular inspections documented.<br>
        - Submit monthly service logs to the Company.
    </div>

    <div class="section">
        <strong>4. COMPANY RESPONSIBILITIES</strong><br>
        - Provide route schedules and passenger estimates at least {{ $noticeDays }} days in advance.<br>
        - Ensure timely payment as per Section 2.<br>
        - Conduct AI-based fraud checks on submitted documentation (results non-binding unless flagged).
    </div>

    <div class="section">
        <strong>5. PERFORMANCE AND COMPLIANCE</strong><br>
        - The Vendor agrees to maintain a minimum on-time performance of {{ $performanceThreshold }}, measured
        monthly.<br>
        - Non-compliance may result in penalties of {{ $penalty }} or termination.
    </div>

    <div class="section">
        <strong>6. TERMINATION</strong><br>
        - Either party may terminate this Contract with {{ $terminationNotice }} days’ written notice.<br>
        - Immediate termination is allowed if the Vendor fails to meet safety standards or commits fraud, as determined
        by the Company.
    </div>

    <div class="section">
        <strong>7. INSURANCE AND LIABILITY</strong><br>
        - Vendor shall carry insurance: {{ $insurance }}.<br>
        - Vendor indemnifies the Company against claims arising from Vendor negligence.
    </div>

    <div class="section">
        <strong>8. DOCUMENTATION AND FRAUD CHECKS</strong><br>
        - Vendor must submit this Contract and supporting documents (e.g., vehicle registration, driver licenses) in PDF
        format.<br>
        - The Company will use AI to verify authenticity; flagged documents require Vendor clarification within
        {{ $clarificationDays }} days.
    </div>

    <div class="section">
        <strong>9. CONFIDENTIALITY</strong><br>
        Both parties agree not to disclose contract terms, payment details, or operational data to third parties.
    </div>

    <div class="section">
        <strong>10. GOVERNING LAW</strong><br>
        This Contract is governed by the laws of {{ $jurisdiction }}.
    </div>

    <div class="section">
        <strong>11. SIGNATURES</strong><br>
        By signing below, both parties agree to the terms of this Contract.
    </div>

    <div class="signature">
        <div>
            COMPANY:<br>
            <span class="underline"></span><br>
            {{ $companyRepName }}<br>
            Title: {{ $companyRepTitle }}<br>
            Date: {{ $date }}<br>
            Contact: {{ $companyContact }}
        </div>
        <div>
            VENDOR:<br>
            <span class="underline"></span><br>
            {{ $vendorName }}<br>
            Title: {{ $vendorTitle }}<br>
            Date: {{ $date }}<br>
            Contact: {{ $vendorContact }}
        </div>
    </div>

    <div class="footer">
        {{ $companyName }} reserves the right to amend terms with mutual consent.
    </div>
</body>

</html>
