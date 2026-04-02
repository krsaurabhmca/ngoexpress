    </div> <!-- Close main-content -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function checkUpdate() {
            if (confirm("Are you sure you want to pull the latest updates from the Git repository?")) {
                var btn = event.target || event.srcElement;
                var oldText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite;"></i> Updating...';
                btn.disabled = true;

                $.ajax({
                    url: 'update_system.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        alert(res.message);
                        btn.innerHTML = oldText;
                        btn.disabled = false;
                        if(res.status === 'success' && res.message.indexOf('Already up to date') === -1) {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        alert("A critical error occurred while attempting to contact the update server.");
                        btn.innerHTML = oldText;
                        btn.disabled = false;
                    }
                });
            }
        }
    </script>
</body>
</html>
