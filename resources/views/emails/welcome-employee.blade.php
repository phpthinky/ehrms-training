<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to EHRMS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
        }
        .welcome-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1e40af;
        }
        .credentials-box {
            background-color: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .credentials-box h3 {
            margin-top: 0;
            color: #1e40af;
            font-size: 16px;
        }
        .credential-item {
            margin: 12px 0;
            display: flex;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #64748b;
            min-width: 100px;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background-color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
            color: #1e293b;
        }
        .password-value {
            font-weight: bold;
            color: #dc2626;
            font-size: 16px;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 14px;
        }
        .info-box strong {
            color: #92400e;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .employee-details {
            background-color: #f1f5f9;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .employee-details h3 {
            margin-top: 0;
            color: #1e40af;
            font-size: 16px;
        }
        .detail-row {
            margin: 10px 0;
            display: flex;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
            min-width: 140px;
        }
        .detail-value {
            color: #1e293b;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            margin: 5px 0;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        ul li {
            margin: 8px 0;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Welcome to EHRMS</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Employee Human Resource Management System</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="welcome-message">Hello <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>,</p>

            <p>Welcome to the team! Your employee account has been successfully created in our Employee Human Resource Management System (EHRMS).</p>

            <!-- Employee Details -->
            <div class="employee-details">
                <h3>Your Profile Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Employee Number:</span>
                    <span class="detail-value">{{ $employee->employee_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Position:</span>
                    <span class="detail-value">{{ $employee->position }}</span>
                </div>
                @if($employee->department)
                <div class="detail-row">
                    <span class="detail-label">Department:</span>
                    <span class="detail-value">{{ $employee->department->name }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Employment Type:</span>
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</span>
                </div>
            </div>

            <!-- Login Credentials -->
            <div class="credentials-box">
                <h3>Your Login Credentials</h3>
                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $employee->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value password-value">{{ $password }}</span>
                </div>
            </div>

            <!-- Important Security Notice -->
            <div class="info-box">
                <p><strong>Important Security Notice:</strong></p>
                <ul style="margin: 10px 0;">
                    <li>Please change your password immediately after your first login</li>
                    <li>Do not share your password with anyone</li>
                    <li>Keep this email secure and delete it after changing your password</li>
                </ul>
            </div>

            <!-- Login Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/login') }}" class="action-button">Login to EHRMS</a>
            </div>

            <p>If you have any questions or need assistance, please contact the HR department.</p>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>Human Resources Department</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>Employee Human Resource Management System</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p style="margin-top: 10px; font-size: 12px;">© {{ date('Y') }} EHRMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
