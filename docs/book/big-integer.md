# Big Integers

`Zend\Math\BigInteger\BigInteger` offers a class to manage arbitrary length
integers. PHP supports integer numbers with a maximum value of `PHP_INT_MAX`, a
value defined by your processor architecture and available memory. If you need
to manage integers bigger than `PHP_INT_MAX`, you need to use external libraries
or PHP extensions such as [GMP](http://php.net/gmp) or [BC Math](http://php.net/bc).

`Zend\Math\BigInteger\BigInteger` is able to manage big integers using either
the GMP or the BC Math extensions as adapters.

## Methods available

The mathematical functions implemented in `Zend\Math\BigInteger\BigInteger` are:

- `add($leftOperand, $rightOperand)`: add two big integers.
- `sub($leftOperand, $rightOperand)`: subtract two big integers.
- `mul($leftOperand, $rightOperand)`: multiply two big integers.
- `div($leftOperand, $rightOperand)`: divide two big integers (this method
  returns only the integer part of result).
- `pow($operand, $exp)`: raise one big integer using the other big integer as
  the exponent.
- `sqrt($operand)`: get the square root of a big integer.
- `abs($operand)`: get the absolute value of a big integer.
- `mod($leftOperand, $modulus)`: get the modulus of dividing one big integer by
  another.
- `powmod($leftOperand, $rightOperand, $modulus)`: raise a big integer using
  another big integer as the exponent, and reduce by the specified modulus.
- `comp($leftOperand, $rightOperand)`: compare two big integers. Returns &lt; 0
  if `$leftOperand` is less than `$rightOperand`; &gt; 0 if `$leftOperand` is greater
  than `$rightOperand`; and 0 if they are equal.
- `intToBin($int, $twoc = false)`: convert a big integer into its binary number
  representation;
- `binToInt($bytes, $twoc = false)`: convert a binary number into a big integer.
- `baseConvert($operand, $fromBase, $toBase = 10)`: convert a big integer
  between arbitrary bases.

## Examples

The example below illustrates using the BC Math adapter to calculate the sum of
two random integers with 100 digits each.

```php
use Zend\Math\BigInteger\BigInteger;
use Zend\Math\Rand;

$bigInt = BigInteger::factory('bcmath');

$x = Rand::getString(100, '0123456789');
$y = Rand::getString(100, '0123456789');

$sum = $bigInt->add($x, $y);
$len = strlen($sum);

printf("%{$len}s +\n%{$len}s =\n%s\n%s\n", $x, $y, str_repeat('-', $len), $sum);
```

Note that the big integers are managed using strings; even the result of the sum
is represented as a string.

Next is an example using the BC Math adapter to generate the binary
representation of a negative big integer containing 100 digits.

```php
use Zend\Math\BigInteger\BigInteger;
use Zend\Math\Rand;

$bigInt = BigInteger::factory('bcmath');

$digits = 100;
$x = '-' . Rand::getString($digits, '0123456789');

$byte = $bigInt->intToBin($x);

printf(
    "The binary representation of a big integer with %d digits:\n%s\nis (in Base64 format): %s\n",
    $digits
    $x,
    base64_encode($byte)
);
printf("Length in bytes: %d\n", strlen($byte));

$byte = $bigInt->intToBin($x, true);

printf(
    "The two's complement binary representation of the big integer with %d digits:\n"
    . "%s\nis (in Base64 format): %s\n",
    $digits,
    $x,
    base64_encode($byte)
);
printf("Length in bytes: %d\n", strlen($byte));
```

The above generates the binary representation of the big integer number using the
default binary format, and the [two's complement](http://en.wikipedia.org/wiki/Two%27s_complement)
representation (specified with the `true` parameter in the `intToBin` function).

