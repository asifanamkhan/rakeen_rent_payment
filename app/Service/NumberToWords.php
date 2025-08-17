<?php

namespace App\Service;

class NumberToWords
{

    public static function numberToWords($num)
    {
        // Handle invalid input
        if (!is_numeric($num)) {
            return 'Invalid number';
        }

        // Convert to float for consistency
        $num = floatval($num);

        // Handle zero
        if ($num == 0) {
            return 'Zero';
        }

        // Handle negative numbers
        if ($num < 0) {
            return 'Negative ' . numberToWords(abs($num));
        }

        // Arrays for number words
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $thousands = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];

        // Helper function to convert numbers less than 1000
        function convertHundreds($n, $ones, $teens, $tens)
        {
            $result = '';

            // Hundreds place
            if ($n >= 100) {
                $result .= $ones[intval($n / 100)] . ' Hundred';
                $n = $n % 100;
                if ($n > 0) $result .= ' ';
            }

            // Tens and ones place
            if ($n >= 20) {
                $result .= $tens[intval($n / 10)];
                $n = $n % 10;
                if ($n > 0) $result .= ' ' . $ones[$n];
            } elseif ($n >= 10) {
                $result .= $teens[$n - 10];
            } elseif ($n > 0) {
                $result .= $ones[$n];
            }

            return $result;
        }

        // Split into integer and decimal parts
        $parts = explode('.', strval($num));
        $integerPart = intval($parts[0]);
        $decimalPart = isset($parts[1]) ? substr($parts[1] . '00', 0, 2) : '';

        $result = '';

        // Convert integer part
        if ($integerPart == 0) {
            $result = 'Zero';
        } else {
            $tempNum = $integerPart;
            $groupIndex = 0;

            while ($tempNum > 0) {
                $group = $tempNum % 1000;
                if ($group != 0) {
                    $groupWords = convertHundreds($group, $ones, $teens, $tens);
                    if ($thousands[$groupIndex] != '') {
                        $result = $groupWords . ' ' . $thousands[$groupIndex] . ($result ? ' ' . $result : '');
                    } else {
                        $result = $groupWords . ($result ? ' ' . $result : '');
                    }
                }
                $tempNum = intval($tempNum / 1000);
                $groupIndex++;
            }
        }

        // Convert decimal part if exists
        if ($decimalPart && intval($decimalPart) > 0) {
            $result .= ' Point ' . convertHundreds(intval($decimalPart), $ones, $teens, $tens);
        }

        return $result;
    }
}
