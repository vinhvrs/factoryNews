<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FactoryNews</title>

  {{-- ← this injects the React Refresh preamble! --}}
  @viteReactRefresh

  {{-- then load your CSS/JS as normal --}}
  @vite([
    'resources/css/app.css',
    'resources/js/main.jsx'
  ])
</head>
<body>
  <div id="app"></div>
</body>
</html>
