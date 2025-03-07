<?php
// Credit Score Calculation Function
function calculateCreditScore($paymentHistory, $debtUtilization, $creditHistoryLength, $newInquiries, $creditMix) {
    // Assign weight multipliers
    $score = ($paymentHistory * 350) + ($debtUtilization * 300) + ($creditHistoryLength * 150) + ($newInquiries * 100) + ($creditMix * 100);
    return round($score);
}

// Function to classify the credit score
function classifyCreditScore($score) {
    if ($score >= 800) {
        return ['category' => 'Excellent', 'risk' => 'Very Low'];
    } elseif ($score >= 700) {
        return ['category' => 'Good', 'risk' => 'Low'];
    } elseif ($score >= 600) {
        return ['category' => 'Fair', 'risk' => 'Moderate'];
    } elseif ($score >= 500) {
        return ['category' => 'Poor', 'risk' => 'High'];
    } else {
        return ['category' => 'Bad', 'risk' => 'Very High'];
    }
}

// Example Input (Replace with real user data from DB)
$paymentHistory = 1.0; // 100% on-time payments
$debtUtilization = 0.6; // 60% of available credit used
$creditHistoryLength = 0.8; // Long credit history
$newInquiries = 0.9; // Few new inquiries
$creditMix = 0.7; // Good mix of credit

// Calculate the Credit Score
$creditScore = calculateCreditScore($paymentHistory, $debtUtilization, $creditHistoryLength, $newInquiries, $creditMix);

// Classify the Credit Score
$classification = classifyCreditScore($creditScore);

// Output the results
$response = [
    'credit_score' => $creditScore,
    'category' => $classification['category'],
    'risk_level' => $classification['risk']
];

header('Content-Type: application/json');
echo json_encode($response);
