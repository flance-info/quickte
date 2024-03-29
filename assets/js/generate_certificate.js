"use strict";
var jsPDF = window.jspdf.jsPDF;

document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.get-certificate').forEach(function (element) {
        element.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            let course_id = this.getAttribute('data-course_id');
            let student_name = this.getAttribute('data-student-name');
            let certificate_code = this.getAttribute('certificate-code');
            let fiscal_code = this.getAttribute('fiscal-code');
            let nonce = this.getAttribute('nonce');
            let started = this.getAttribute('started');

            getCertificateChild(id, course_id, student_name, certificate_code, fiscal_code, nonce, started);
        });
    });
});


function getCertificateChild(id, courseId, student_name, certificate_code, fiscal_code, nonce, started) {
    var url = stm_lms_ajaxurl + '?action=stm_get_certificate&nonce=' + nonce + '&post_id=' + id + '&course_id=' + courseId;
    jQuery.ajax({
        url: url,
        method: 'get',
        success: function success(data) {
            if (typeof data.data !== 'undefined') {

                const unixTimestamp = started * 1000;
                const dateObject = new Date(unixTimestamp);
                const day = dateObject.getDate();
                const month = dateObject.toLocaleString('default', { month: 'long' });
                const year = dateObject.getFullYear();
                const formattedDate = `${day} ${month} ${year}`;

                console.log(data.data )
                data.data.fields[1].content = student_name
                data.data.fields[11].content = formattedDate

                if (fiscal_code) {
                    data.data.fields[15].content = fiscal_code
                } else {
                    data.data.fields[14].content = '';
                }
                data.data.fields[15].content = fiscal_code
                data.data.fields[17].content = certificate_code

                generateCertificateChild(data.data);
            }
        }
    });
}

function generateCertificateChild(data) {
    var orientation = data.orientation;
    var doc = new jsPDF({
        orientation: orientation,
        unit: 'px',
        format: [600, 900]
    });
    doc.addFileToVFS('OpenSans-Regular-normal.ttf', openSansRegular);
    doc.addFont('OpenSans-Regular-normal.ttf', 'OpenSans', 'normal');
    doc.addFileToVFS('OpenSans-Bold-normal.ttf', openSansBold);
    doc.addFont('OpenSans-Bold-normal.ttf', 'OpenSans', 'bold');
    doc.addFileToVFS('OpenSans-BoldItalic-normal.ttf', openSansBoldItalic);
    doc.addFont('OpenSans-BoldItalic-normal.ttf', 'OpenSans', 'bolditalic');
    doc.addFileToVFS('OpenSans-Italic-italic.ttf', openSansItalic);
    doc.addFont('OpenSans-Italic-italic.ttf', 'OpenSans', 'italic');
    doc.addFileToVFS('Montserrat-normal.ttf', montserratRegular);
    doc.addFont('Montserrat-normal.ttf', 'Montserrat', 'normal');
    doc.addFileToVFS('Montserrat-bold.ttf', montserratBold);
    doc.addFont('Montserrat-bold.ttf', 'Montserrat', 'bold');
    doc.addFileToVFS('Montserrat-italic.ttf', montserratItalic);
    doc.addFont('Montserrat-italic.ttf', 'Montserrat', 'italic');
    doc.addFileToVFS('Montserrat-bolditalic.ttf', montserratBoldItalic);
    doc.addFont('Montserrat-bolditalic.ttf', 'Montserrat', 'bolditalic');
    doc.addFileToVFS('Merriweather-normal.ttf', merriweatherRegular);
    doc.addFont('Merriweather-normal.ttf', 'Merriweather', 'normal');
    doc.addFileToVFS('Merriweather-bold.ttf', merriweatherBold);
    doc.addFont('Merriweather-bold.ttf', 'Merriweather', 'bold');
    doc.addFileToVFS('Merriweather-italic.ttf', merriweatherItalic);
    doc.addFont('Merriweather-italic.ttf', 'Merriweather', 'italic');
    doc.addFileToVFS('Merriweather-bolditalic.ttf', merriweatherBoldItalic);
    doc.addFont('Merriweather-bolditalic.ttf', 'Merriweather', 'bolditalic');
    doc.addFileToVFS('Katibeh-normal.ttf', katibeh);
    doc.addFont('Katibeh-normal.ttf', 'Katibeh', 'normal');
    doc.addFont('Katibeh-normal.ttf', 'Katibeh', 'bold');
    doc.addFont('Katibeh-normal.ttf', 'Katibeh', 'italic');
    doc.addFont('Katibeh-normal.ttf', 'Katibeh', 'bolditalic');
    doc.addFileToVFS('Amiri-normal.ttf', Amiri);
    doc.addFont('Amiri-normal.ttf', 'Amiri', 'normal');
    doc.addFont('Amiri-normal.ttf', 'Amiri', 'bold');
    doc.addFont('Amiri-normal.ttf', 'Amiri', 'italic');
    doc.addFont('Amiri-normal.ttf', 'Amiri', 'bolditalic');
    doc.addFileToVFS('Oswald-normal.ttf', oswald);
    doc.addFont('Oswald-normal.ttf', 'Oswald', 'normal');
    doc.addFont('Oswald-normal.ttf', 'Oswald', 'italic');
    doc.addFileToVFS('Oswald-bold.ttf', oswaldBold);
    doc.addFont('Oswald-bold.ttf', 'Oswald', 'bold');
    doc.addFont('Oswald-bold.ttf', 'Oswald', 'bolditalic');
    var background = data.image;
    if (background) {
        if (orientation === 'portrait') {
            doc.addImage(background, "JPEG", 0, 0, 600, 900, '', 'NONE');
        } else {
            doc.addImage(background, "JPEG", 0, 0, 900, 600, '', 'NONE');
        }
    }
    data.fields.forEach(function (field) {
        if (field.content) {
            if (field.type === 'image') {
                if (typeof field.content !== 'undefined' && field.content) {
                    doc.addImage(field.content, "JPEG", parseInt(field.x), parseInt(field.y), parseInt(field.w), parseInt(field.h));
                }
            } else {
                var textColor = hexToRGBChild(field.styles.color.hex);
                var r = textColor.r;
                var g = textColor.g;
                var b = textColor.b;
                var fontSize = parseInt(field.styles.fontSize.replace('px', ''));
                var fieldWidth = parseInt(field.w) - 12;
                var x = parseInt(field.x);
                var y = parseInt(field.y) + fontSize * 0.8;
                if (field.styles.textAlign === 'right') {
                    x = x + fieldWidth;
                } else if (field.styles.textAlign === 'center') {
                    x = x + 6 + fieldWidth / 2;
                } else {
                    x = x + 6;
                }
                var options = {
                    maxWidth: fieldWidth,
                    align: field.styles.textAlign,
                    lineHeightFactor: 1.25
                };
                var fontStyle = 'normal';
                if (field.styles.fontWeight && field.styles.fontWeight !== "false") {
                    fontStyle = 'bold';
                    if (field.styles.fontStyle && field.styles.fontStyle !== "false") {
                        fontStyle = 'bolditalic';
                    }
                } else if (field.styles.fontStyle && field.styles.fontStyle !== "false") {
                    fontStyle = 'italic';
                }
                doc.setTextColor(field.styles.color.hex);
                doc.setFontSize(fontSize);
                doc.setFont(field.styles.fontFamily, fontStyle);
                doc.text(field.content, x, y, options);
            }
        }
    });
    var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    if (isSafari) {
        doc.autoPrint();
        doc.output('save', 'Certificate.pdf');
    } else {
        // For other browsers, use window.open as before.
        window.open(doc.output('bloburl'));
    }
}

function hexToRGBChild(h) {
    var r = 0,
        g = 0,
        b = 0;

    // 3 digits
    if (h.length == 4) {
        r = "0x" + h[1] + h[1];
        g = "0x" + h[2] + h[2];
        b = "0x" + h[3] + h[3];

        // 6 digits
    } else if (h.length == 7) {
        r = "0x" + h[1] + h[2];
        g = "0x" + h[3] + h[4];
        b = "0x" + h[5] + h[6];
    }
    return {
        r: r,
        g: g,
        b: b
    };
}
