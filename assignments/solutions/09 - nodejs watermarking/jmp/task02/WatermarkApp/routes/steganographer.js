/**
 * Created by Tobi on 19/12/2016.
 */
var express = require('express');
var router = express.Router();
var path = require('path');
var fs = require('fs');

// TODO make sure the dependencies already exist --> npm install --save stegosaurus uuid
var stego = require('stegosaurus');
var uuid = require('uuid');

// TODO set the correct directory (static) for the stegano module that you have to create first.
// put an index.html in there. (see skeleton)
var steganoDir = path.join(__dirname, '../stegano');
var secretImagesDir = path.join(steganoDir, 'watermarked');

// change this to anything you like. make sure the image exists and is a PNG!
var hostImage = path.join(__dirname, '../public/images/', 'merry-x-mas.png');


// make sure the folder exists.
if (!fs.existsSync(secretImagesDir)) {
    fs.mkdirSync(secretImagesDir);
}

// serves the content (index.html and watermarked images)
router.use(express.static(steganoDir));

// hides or seeks the secret messages.
router.use('/:method', function (req, res, next) {
    var id;
    var generatedImage;
    var message;
    var size;

    /**
     * generates a valid path for the image that contains the message.
     *
     * @param id of the image. Either created or read from the request.
     */
    function makeImagePath(id) {
        return path.join(secretImagesDir, id + '.png');
    }

    // what do we want to do?
    // hide --> encode

    if (req.params.method === 'hide') {
        id = uuid.v4();
        console.log("Hide!");
        if (req.body.message) {
            console.log("POST message!");
            console.log(JSON.stringify(req.body));
            console.log(JSON.stringify(req.query));
            message = req.body.message;
        }

        if (message) {
            // message found, set to image
            var image = makeImagePath(id);
            stego.encodeString(hostImage, image, message, function (err) {
                if (err) {
                    throw err;
                }
                console.log("image created!");

                image = image.substr(image.indexOf('/stegano'));
                res.send({
                    id: id,
                    path: image,
                    size: message.length
                })
            });
        }

        // seek --> decode the image.
    } else if (req.params.method === 'seek') {
        // the mandatory parameter is the id of the image.

        console.log("seek!");
        if (req.query.id) {
            console.log("GET call!");
            id = req.query.id;
            size = req.query.size;
        }

        if (id) {
            console.log(JSON.stringify(req.query));

            stego.decode(makeImagePath(id), size, function (message, err) {
                if (err) {
                    throw err;
                }
                console.log("image decoded!");

                res.send(JSON.stringify({
                    status: 'successfully decoded message',
                    message: message
                }));
            });
        } else {
            console.log("id not set!");
        }
    } else {
        // method did not match anything that we can handle.
        next();
    }
});


module.exports = router;
