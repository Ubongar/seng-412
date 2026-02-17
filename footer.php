    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Confirm Dialog -->
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-dialog">
            <div class="confirm-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <h3 id="confirmTitle">Are you sure?</h3>
            <p id="confirmMessage">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn btn-secondary" onclick="closeConfirm()">Cancel</button>
                <button class="btn btn-danger" id="confirmBtn" onclick="executeConfirm()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3><i class="fas fa-code"></i> SENG412 Group 3b Project</h3>
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
            <p>&copy; <?= date('Y') ?> SENG412 Group 3b Project. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Nav toggle
        document.getElementById('navToggle').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('show');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });

        // ===== Toast Notifications =====
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.innerHTML = '<i class="fas ' + (icons[type] || icons.info) + '"></i><span class="toast-message">' + message + '</span>';
            toast.onclick = () => toast.remove();
            container.appendChild(toast);
            setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
        }

        // ===== Confirm Dialog =====
        let confirmCallback = null;

        function showConfirm(title, message, callback) {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            confirmCallback = callback;
            document.getElementById('confirmOverlay').classList.add('active');
        }

        function closeConfirm() {
            document.getElementById('confirmOverlay').classList.remove('active');
            confirmCallback = null;
        }

        function executeConfirm() {
            if (confirmCallback) confirmCallback();
            closeConfirm();
        }

        // ===== Modal Helpers =====
        function openModal(id) {
            document.getElementById(id).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal on overlay click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('active');
                document.body.style.overflow = '';
            }
            if (e.target.classList.contains('confirm-overlay')) {
                closeConfirm();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(m => {
                    m.classList.remove('active');
                });
                closeConfirm();
                document.body.style.overflow = '';
            }
        });

        // ===== AJAX Helper =====
        function apiRequest(action, entity, data, method = 'POST') {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('action', action);
                formData.append('entity', entity);
                if (data) {
                    Object.keys(data).forEach(key => formData.append(key, data[key]));
                }

                let url = 'api.php';
                let options = {};

                if (method === 'GET') {
                    const params = new URLSearchParams();
                    params.append('action', action);
                    params.append('entity', entity);
                    if (data) Object.keys(data).forEach(key => params.append(key, data[key]));
                    url += '?' + params.toString();
                    options = { method: 'GET' };
                } else {
                    options = { method: 'POST', body: formData };
                }

                fetch(url, options)
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            resolve(result);
                        } else {
                            reject(result.message || 'Operation failed');
                        }
                    })
                    .catch(err => reject('Network error: ' + err.message));
            });
        }
    </script>
</body>
</html>
