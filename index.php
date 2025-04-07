<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    $contactMethod = $_POST['contactMethod'];
    
    // Handle file upload
    if (isset($_FILES['documentUpload']) && $_FILES['documentUpload']['error'] == 0) {
        $fileTmpPath = $_FILES['documentUpload']['tmp_name'];
        $fileName = $_FILES['documentUpload']['name'];
        $fileSize = $_FILES['documentUpload']['size'];
        $fileType = $_FILES['documentUpload']['type'];
        
        // Specify the directory to save the uploaded file
        $uploadFileDir = './uploaded_files/';
        $dest_path = $uploadFileDir . $fileName;

        // Move the file to the specified directory
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            // File is successfully uploaded
            $fileUploadStatus = "File is successfully uploaded.";
        } else {
            $fileUploadStatus = "There was an error moving the uploaded file.";
        }
    }

    $encodedMessage = urlencode("Name: $name\nSubject: $subject\n\nMessage: $message\n\nFile: $dest_path");

    if ($contactMethod === 'whatsapp') {
        header("Location: https://wa.me/12815070243?text=$encodedMessage");
    } else {
        header("Location: mailto:virtualacademia01@gmail.com?subject=Contact%20Request&body=$encodedMessage");
    }
}
?>

<!-- Contact Us Section -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s" id="contact">
    <div class="container">
        <div class="text-center">
            <h6 class="section-title bg-white text-center text-primary px-3">Contact Us</h6>
            <h1 class="mb-4">Get in Touch</h1>
            <div class="speech-bubble mx-auto mb-4" style="max-width: 600px; padding: 20px; background-color: #f1f1f1; border-radius: 20px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); position: relative;">
                <i class="fas fa-comment-dots me-2 text-primary"></i>
                <p class="lead text-muted mb-0" style="font-size: 1.1rem;">
                    We respond fast and provide the best customer service and support. Your satisfaction is our priority!
                </p>
                <div style="position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 10px solid transparent; border-right: 10px solid transparent; border-top: 20px solid #f1f1f1;"></div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 shadow p-4">
                <form id="contactForm" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required pattern="[A-Za-z\s]+">
                                <label for="name">Your Name</label>
                                <div class="invalid-feedback">Please provide your name.</div>
                            </div>
                        </div>

                        <!-- Subject Dropdown -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="" disabled selected>Select a Subject</option>
                                    <option value="Calculus & Advanced Math">Calculus & Advanced Math</option>
                                    <option value="Physics (All Levels)">Sciences (Chemistry, Biology & Physics)</option>
                                    <option value="Organic Chemistry"> Chemistry</option>
                                    <option value="Molecular Biology">Nursing & Biology</option>
                                    <option value="Academic Writing & Research">Academic Writing & Research</option>
                                    <option value="Programming (Python/Java)">Programming (R studio/SPSS/Tableau)</option>
                                    <option value="Statistics & Data Analysis">Statistics & Data Analysis</option>
                                    <option value="Business & Finance">Business & Finance</option>
                                    <option value="Other" data-placeholder="please specify">Other</option>
                                </select>
                                <label for="subject">Course / Subject</label>
                                <div class="invalid-feedback">Please select a subject.</div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave your message here" id="message" name="message" style="height: 120px" required></textarea>
                                <label for="message">Your Message</label>
                                <div class="invalid-feedback">Please enter your message.</div>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="col-12 text-center mt-4">
                            <p class="mb-3">Upload your document (max 100MB):</p>
                            <input type="file" class="form-control-file" name="documentUpload" accept=".pdf,.doc,.docx,.txt" required>
                        </div>

                        <!-- Contact Method -->
                        <div class="col-12 text-center">
                            <p class="mb-3">How would you like to contact us?</p>
                            <button type="submit" class="btn btn-primary btn-sm mx-2 glow-icon" name="contactMethod" value="whatsapp">
                                <i class="fab fa-whatsapp me-2"></i> WhatsApp
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm mx-2 glow-icon" name="contactMethod" value="email">
                                <i class="fas fa-envelope me-2"></i> Email
                            </button>
                            <a href="https://www.fiverr.com/s/WEz8A3X" target="_blank" class="btn btn-success btn-sm mx-2 glow-icon fiverr">
                                <img src="img/fiverr.png" alt="Fiverr" style="height: 1rem; margin-right: 0.5rem;"> Fiverr
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Contact Us Section End -->
