// Note: Include this script globally 

// Global variable declaration
var _gblPositive = [];
var _gblNegative = [];

// Returns TRUE if keycode/charcode is numeric
function _gblIsNumeric3dec(e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    return (charCode >= 48 && charCode <= 57 || charCode == 13) ? true : false;
};

// Returns a formatted amount based on positive and negative values
function _gblConcatAmount3dec(positive, negative) {
    var _positive = (positive.length > 0) ? positive.join('') : '0';
    var _negative = '000';

    if (negative.length == 3) {
        _negative = negative.join('');
    } else if (negative.length == 2) {
        _negative = negative.join('') + '0';
    } else {
        _negative = negative.join('') + '00';
    }

    var _amount = parseFloat(_positive + '.' + _negative).toLocaleString(undefined, {
        minimumFractionDigits: 3,
        maximumFractionDigits: 3
    });
    return _amount;
};

// Keydown event listener
function _gblOnKeyDown3dec(e) {
    var charCode = (e.which) ? e.which : event.keyCode;

    // Period/Dot keypress
    if (charCode == 190) {
        this.classList.add('dot-enabled');
        this.classList.remove('focused');
        _gblNegative = [];
        return;
    }

    // Delete keypress
    if (charCode == 46 || charCode == 8) {
        e.target.value = '0.000';
        this.classList.remove('dot-enabled');
        _gblNegative = [];
        _gblPositive = [];
        return;
    }

    // Backspace keypress
    // if (charCode == 8) {
    //     return e.preventDefault();
    // }

    var dotEnabled = this.classList.contains('dot-enabled');
    var focused = this.classList.contains('focused');

    if (!dotEnabled && focused) {
        _gblPositive = [];
        this.classList.remove('focused');
        return;
    }
};

// Keypress event listener
function _gblOnKeyPress3dec(e) {
    e.preventDefault();

    if (_gblIsNumeric3dec(e)) {
        var dotEnabled = this.classList.contains('dot-enabled');
        if (!dotEnabled) {
            _gblPositive.push(e.key);
        }

        if (dotEnabled && _gblNegative.length <= 2) {
            _gblNegative.push(e.key);
        }

        this.value = _gblConcatAmount3dec(_gblPositive, _gblNegative);
    }
    return;
};

// Focus event listener
function _gblOnFocus3dec(e) {
    this.classList.add('focused');
    this.classList.remove('dot-enabled');
    _gblPositive = Array.from(e.target.value.split('.')[0].replace(/,/g, ''));
    _gblNegative = Array.from(e.target.value.split('.')[1].replace(/,/g, ''));
    return;
};

// Blur event listener
function _gblOnBlur3dec(e) {
    this.classList.remove('focused');
    this.classList.remove('dot-enabled');
    return;
};

/**
 * param: className
 * Gets all input fields with numeric classes and binds the event listeners
 * CALL this function in every module of the program that requires numeric key binding 
 */
function _gblBindNumericClasses3dec(className) {
    var inputNumFields = document.querySelectorAll('.' + className);
    inputNumFields.forEach(input => {
        input.addEventListener('keydown', _gblOnKeyDown3dec);
        input.addEventListener('keypress', _gblOnKeyPress3dec);
        input.addEventListener('focus', _gblOnFocus3dec);
        input.addEventListener('blur', _gblOnBlur3dec);
    });
};