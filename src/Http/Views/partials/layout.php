<?php
/**
 * VISTA PARCIAL: layout.php
 * 
 * S'inclou a l'inici de cada vista per tenir la capçalera i la nav comú.
 * Evita repetir el <head> i el <nav> a cada fitxer.
 */
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management — DDD</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f4f8; color: #2d3748; }

        /* Barra de navegació principal */
        nav {
            background: #2b6cb0;
            padding: 1rem 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 6px rgba(0,0,0,.2);
        }
        nav .brand { font-size: 1.15rem; font-weight: 700; color: white; margin-right: auto; }
        nav a {
            color: rgba(255,255,255,.9);
            text-decoration: none;
            font-size: .9rem;
            padding: .4rem .9rem;
            border-radius: 6px;
            transition: background .2s;
        }
        nav a:hover { background: rgba(255,255,255,.2); }

        /* Contingut principal */
        main { max-width: 960px; margin: 2rem auto; padding: 0 1.5rem; }

        /* Títols */
        h1 { font-size: 1.6rem; margin-bottom: 1.5rem; color: #1a365d; }
        h2 { font-size: 1.1rem; margin-bottom: 1rem; color: #2c5282; }

        /* Cards (contenidors blancs amb ombra) */
        .card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 1px 5px rgba(0,0,0,.08);
            margin-bottom: 1.5rem;
        }

        /* Taules */
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .7rem 1rem; border-bottom: 1px solid #e2e8f0; font-size: .9rem; }
        th { background: #ebf4ff; color: #2b6cb0; font-weight: 600; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f7faff; }

        /* Botons */
        .btn {
            display: inline-block;
            padding: .5rem 1.2rem;
            background: #2b6cb0;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: .9rem;
            transition: background .2s;
        }
        .btn:hover { background: #2c5282; }

        /* Formularis */
        label { display: block; margin-bottom: .3rem; font-weight: 500; font-size: .9rem; color: #4a5568; }
        input[type=text], input[type=email], input[type=number], select {
            width: 100%;
            padding: .55rem .9rem;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            font-size: .9rem;
            margin-bottom: 1rem;
            background: white;
        }
        input:focus, select:focus { outline: none; border-color: #63b3ed; box-shadow: 0 0 0 3px rgba(99,179,237,.3); }

        /* Missatge d'error */
        .error {
            background: #fff5f5;
            border: 1px solid #fc8181;
            color: #c53030;
            padding: .8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.2rem;
            font-size: .9rem;
        }

        /* Text buit */
        .empty { color: #a0aec0; font-style: italic; text-align: center; padding: 1.5rem; }

        /* Badges d'estat */
        .badge-ok  { color: #276749; background: #c6f6d5; padding: .2rem .6rem; border-radius: 999px; font-size: .8rem; }
        .badge-no  { color: #744210; background: #fefcbf; padding: .2rem .6rem; border-radius: 999px; font-size: .8rem; }
    </style>
</head>
<body>
<nav>
    <span class="brand">🏫 School Management</span>
    <a href="index.php?route=student">Estudiants</a>
    <a href="index.php?route=teacher">Professors</a>
    <a href="index.php?route=course">Cursos</a>
    <a href="index.php?route=subject">Assignatures</a>
    <a href="index.php?route=enroll">Matriculació</a>
    <a href="index.php?route=assign">Assignació Prof.</a>
</nav>
<main>
