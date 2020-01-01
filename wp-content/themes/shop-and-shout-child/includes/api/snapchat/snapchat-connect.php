    <script>
       window.snapKitInit = function () {
        var loginButtonIconId = 'snapchat_button';
        // Mount Login Button
        snap.loginkit.mountButton(loginButtonIconId, {
          clientId: 'your-clientId',
          redirectURI: 'your-redirectURI',
          scopeList: [
            'user.display_name',
            'user.bitmoji.avatar',
          ],
          handleResponseCallback: function() {
            snap.loginkit.fetchUserInfo()
              .then(data => console.log('User info:', data));
          },
        });
      };

      // Load the SDK asynchronously
      (function (d, s, id) {
        var js, sjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://sdk.snapkit.com/js/v1/login.js";
        sjs.parentNode.insertBefore(js, sjs);
      }(document, 'script', 'loginkit-sdk'));
    </script>
  </body>
</html>
