// Note: Include this script globally 

// Global variable declaration
var _gblPositive = [];
var _gblNegative = [];

// Returns TRUE if keycode/charcode is numeric
function _gblIsNumeric(e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    return (charCode >= 48 && charCode <= 57 || charCode == 13) ? true : false;
};

// Returns a formatted amount based on positive and negative values
function _gblConcatAmount(positive, negative) {
    var _positive = (positive.length > 0) ? positive.join('') : '0';
    var _negative = '00';

    if (negative.length == 2) {
        _negative = negative.join('');
    } else if (negative.length == 1) {
        _negative = negative.join('') + '0';
    }

    var _amount = parseFloat(_positive + '.' + _negative).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    return _amount;
};

// Keydown event listener
function _gblOnKeyDown(e) {
    var charCode = (e.which) ? e.which : event.keyCode;

    // Period/Dot keypress
    if (charCode == 190) {
        this.classList.add('dot-enabled');
        this.classList.remove('focused');
        _gblNegative = [];
        return;
    }

    // Delete keypress
    if (charCode == 46) {
        e.target.value = '0.00';
        this.classList.remove('dot-enabled');
        _gblNegative = [];
        _gblPositive = [];
        return;
    }

    // Backspace keypress
    if (charCode == 8) {
        return e.preventDefault();
    }

    var dotEnabled = this.classList.contains('dot-enabled');
    var focused = this.classList.contains('focused');

    if (!dotEnabled && focused) {
        _gblPositive = [];
        this.classList.remove('focused');
        return;
    }
};

// Keypress event listener
function _gblOnKeyPress(e) {
    e.preventDefault();

    if (_gblIsNumeric(e)) {
        var dotEnabled = this.classList.contains('dot-enabled');
        if (!dotEnabled) {
            _gblPositive.push(e.key);
        }

        if (dotEnabled && _gblNegative.length <= 1) {
            _gblNegative.push(e.key);
        }

        this.value = _gblConcatAmount(_gblPositive, _gblNegative);
    }
    return;
};

// Focus event listener
function _gblOnFocus(e) {
    // this.style.color = '#c2fca4';
    this.style.border = '2.5px solid #cbffbd';
    this.style.opacity = '0.8';
    this.classList.add('focused');
    this.classList.remove('dot-enabled');
    _gblPositive = Array.from(e.target.value.split('.')[0].replace(/,/g, ''));
    _gblNegative = Array.from(e.target.value.split('.')[1].replace(/,/g, ''));
    return;
};

// Blur event listener
function _gblOnBlur(e) {
    // this.style.color = '#ffffff';
    this.style.border = '';
    this.classList.remove('focused');
    this.classList.remove('dot-enabled');
    return;
};

/**
 * param: className
 * Gets all input fields with numeric classes and binds the event listeners
 * CALL this function in every module of the program that requires numeric key binding 
 */
function _gblBindNumericClasses(className) {
    var inputNumFields = document.querySelectorAll('.' + className);
    inputNumFields.forEach(input => {
        input.addEventListener('keydown', _gblOnKeyDown);
        input.addEventListener('keypress', _gblOnKeyPress);
        input.addEventListener('focus', _gblOnFocus);
        input.addEventListener('blur', _gblOnBlur);
    });
};