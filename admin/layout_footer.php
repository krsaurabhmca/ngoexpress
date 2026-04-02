    </div> <!-- Close main-content -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Native System Auto-check for Update versions
        $(document).ready(function() {
            var btn = document.getElementById('system-update-btn');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite;"></i> Checking...';
                $.ajax({
                    url: 'update_system.php?action=check',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if(res.status === 'success' && res.update_available) {
                            btn.innerHTML = '<i class="bi bi-cloud-download"></i> Update to v' + res.remote_version;
                            btn.style.background = '#22c55e'; // Green pulse
                            btn.style.boxShadow = '0 0 15px rgba(34, 197, 94, 0.4)';
                            btn.style.color = '#fff';
                        } else {
                            btn.innerHTML = '<i class="bi bi-check2-circle"></i> Up to Date';
                            btn.style.background = 'transparent';
                            btn.style.border = '1px solid #334155';
                            btn.style.color = '#94a3b8';
                        }
                    },
                    error: function() {
                        btn.innerHTML = 'Update Check Failed';
                    }
                });
            }
        });

        function checkUpdate() {
            var btn = document.getElementById('system-update-btn');
            if (btn.innerText.indexOf('Up to Date') !== -1) {
                alert("You are already running the latest framework version.");
                return;
            }
            if (confirm("System will fetch and merge latest upstream commits. Continue?")) {
                var oldText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite;"></i> Pulling Updates...';
                btn.disabled = true;

                $.ajax({
                    url: 'update_system.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        alert(res.message);
                        if(res.status === 'success' && res.message.indexOf('Already up to date') === -1) {
                            window.location.reload();
                        } else {
                            btn.innerHTML = '<i class="bi bi-check2-circle"></i> Up to Date';
                            btn.style.background = 'transparent';
                            btn.style.border = '1px solid #334155';
                            btn.style.color = '#94a3b8';
                            btn.disabled = false;
                        }
                    },
                    error: function() {
                        alert("Update execution failed. Verify Git connectivity.");
                        btn.innerHTML = oldText;
                        btn.disabled = false;
                    }
                });
            }
        }
    </script>
</body>
</html>
