<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/mention_legale.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/menu.css">
</head>

<body>

    <?php
    include 'menu.php';
    ?>

    <section class="box_mention">

        
        <div id="texte_container"></div>

        <script>
            fetch('http://s1065353875.onlinehome.fr/ml09_wp/wp-json/wp/v2/mention_legale?embed&acf_format=standard')
            .then(res => res.json())
            .then(data => {
            document.getElementById('texte_container').innerHTML = data[0].acf.texte;
            })
            .catch(err => console.error('Erreur lors du chargement du texte :', err));
        </script>

        <p>Créé par Elisa DEVERCHIN </p>

    </section>

    <?php
    include 'footer.php';
    ?>

</body>

</html>