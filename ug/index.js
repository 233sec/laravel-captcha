fs = require('fs');

js = fs.readFileSync('resources/views/frontend/captcha/api.js').toString();
ugly = require('./src/index')(js);
fs.writeFile('resources/views/frontend/captcha/apijs_ugly.blade.php', ugly, function (err) { if (err) throw err; console.log('It\'s saved!'); });


js = fs.readFileSync('resources/views/frontend/captcha/message.blade.php').toString();
ugly = require('./src/index')(js);
fs.writeFile('resources/views/frontend/captcha/message_ugly.blade.php', ugly, function (err) { if (err) throw err; console.log('It\'s saved!'); });

js = fs.readFileSync('resources/views/frontend/captcha/fall.js').toString();
ugly = require('./src/index')(js);
fs.writeFile('resources/views/frontend/captcha/fall_ugly.blade.php', ugly, function (err) { if (err) throw err; console.log('It\'s saved!'); });



js = fs.readFileSync('resources/views/frontend/captcha/js.js').toString();
ugly = require('./src/index')(js);
fs.writeFile('resources/views/frontend/captcha/js_ugly.blade.php', ugly, function (err) { if (err) throw err; console.log('It\'s saved!'); });
