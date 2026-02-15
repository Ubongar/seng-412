    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3><i class="fas fa-code"></i> SENG412 Group Project</h3>
                <p>Internet Technologies and Web Applications Development</p>
                <p>Department of Software Engineering</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="payroll.php"><i class="fas fa-chevron-right"></i> Payroll</a></li>
                    <li><a href="gpa.php"><i class="fas fa-chevron-right"></i> GPA</a></li>
                    <li><a href="details.php"><i class="fas fa-chevron-right"></i> Details</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Course Info</h3>
                <p><strong>Course:</strong> SENG412</p>
                <p><strong>Lecturer:</strong> Idowu Sunday</p>
                <p><strong>Semester:</strong> 2025/2026</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> SENG412 Group Project. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Mobile Navigation Script -->
    <script>
        document.getElementById('navToggle').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('show');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
    </script>
</body>
</html>
