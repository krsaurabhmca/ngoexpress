    </div> <!-- Close main-content -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function checkUpdate() {
            if (confirm("Are you sure you want to check for updates? This will pull the latest version from the repository.")) {
                alert("This feature is currently configured to connect with Git version management. Initial pull initiated...");
                // In production, this would trigger a git pull or auto-update script.
            }
        }
    </script>
</body>
</html>
