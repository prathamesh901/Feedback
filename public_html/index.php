<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form Download</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
        <div class="box">
            <a href="#popup-box">Click To Download Feedback</a>
        </div>
        <div id="popup-box" class="modal">
            <div class="content">
                <div class="container">
                    <div class="top">
                        <img src="collegelogo.png" alt="" class="image">
                        <div class="college-name">
                            <span>Vasantdada Patil Pratishthan's College of Engineering and Visual Arts</span> <br>
                            <span class="text">(Formerly known as Padmabhushan Vasantdada Patil Pratishthan's College of Engineering) <br>
                                TRUST REGD. NO. F 6736 (MUMBAI) <br>
                                NAAC ACCREDITATION FOR 5 YEARS: (28.02.2019 to 27.03.2024)</span>
                            </div>
                        </div>
                        <h1>Download Feedback Form </h1>
                    <form action="download.php" method="post">
                        <div class="select">
                            <div class="year">
                                <label for="year">Select Year:</label>
                                <select name="year" id="year">
                                    <option value="SE">SE</option>
                                    <option value="TE">TE</option>
                                    <option value="BE">BE</option>
                                </select>
                            </div>
                            <div class="brach">
                                <label for="branch">Select Branch:</label>
                                <select name="branch" id="branch">
                                    <option value="CS">CS</option>
                                    <option value="IT">IT</option>
                                    <option value="AIDS">AIDS</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="button">Download Feedback Form</button>
                    </form>
                </div>
                <a href="#" class="box-close"> × </a>
            </div>
        </div>
</body>

</html>