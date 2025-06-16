# Guide Concis : Tester l'API Laravel avec Flutter en Local

Ce guide vous explique comment configurer et tester rapidement votre API Laravel en local en utilisant l'exemple d'application Flutter fourni.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :
- Git
- PHP (version compatible avec le projet Laravel, généralement ^8.1 ou ^8.2)
- Composer (gestionnaire de dépendances PHP)
- Flutter SDK (pour l'application Flutter)
- Un éditeur de code (ex: VS Code)
- (Optionnel) Un client API graphique comme Postman ou Insomnia pour tester l'API indépendamment.

## Étape 1: Démarrer l'API Laravel Locale

1.  **Cloner le Référentiel (si ce n'est pas déjà fait) :**
    ```bash
    git clone <URL_DE_VOTRE_REPOSITORY>
    cd <NOM_DU_DOSSIER_DU_PROJET>
    ```

2.  **Installer les Dépendances PHP :**
    ```bash
    # Si Composer est installé globalement
    composer install
    # Sinon, si vous avez composer.phar dans le répertoire du projet
    # php composer.phar install
    ```
    *(Dans l'environnement de développement initial de ce projet, `php /app/composer.phar install` a été utilisé, adaptez selon votre configuration.)*

3.  **Configurer le Fichier d'Environnement (`.env`) :**
    - Copiez `.env.example` vers `.env` :
      ```bash
      cp .env.example .env
      ```
    - Générez la clé d'application :
      ```bash
      php artisan key:generate
      ```

4.  **Configurer la Base de Données (SQLite) :**
    - Dans votre fichier `.env`, assurez-vous que les lignes suivantes sont configurées pour SQLite :
      ```dotenv
      DB_CONNECTION=sqlite
      # Commentez ou supprimez les autres variables DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
      ```
    - Créez le fichier de base de données SQLite (s'il n'existe pas) :
      ```bash
      touch database/database.sqlite
      ```

5.  **Exécuter les Migrations de la Base de Données :**
    Cela créera toutes les tables nécessaires, y compris pour les utilisateurs, articles, etc.
    ```bash
    php artisan migrate
    ```

6.  **Exécuter les Seeders (pour les données initiales) :**
    Cela créera un utilisateur de test avec les identifiants `test@example.com` et le mot de passe `password`.
    ```bash
    php artisan db:seed
    ```
    *(Si vous souhaitez créer d'autres utilisateurs ou données, vous pouvez modifier `DatabaseSeeder.php` ou créer des factories et utiliser `php artisan tinker`.)*

7.  **Démarrer le Serveur de Développement Laravel :**
    ```bash
    php artisan serve
    ```
    Notez l'URL affichée (généralement `http://127.0.0.1:8000`). C'est l'URL de base de votre API.

## Étape 2: Configurer et Lancer l'Exemple Flutter

1.  **Préparer l'Environnement Flutter :**
    - Assurez-vous que le Flutter SDK est installé et que la commande `flutter` est disponible dans votre terminal.
    - Vous aurez besoin d'un émulateur Android, d'un simulateur iOS, ou d'un appareil physique configuré pour le développement.

2.  **Créer un Nouveau Projet Flutter (ou utiliser un existant) :**
    Si vous partez de zéro pour le test :
    ```bash
    flutter create test_api_flutter_app
    cd test_api_flutter_app
    ```
    Sinon, naviguez vers le répertoire de votre application Flutter existante.

3.  **Intégrer le Code de l'Exemple :**
    Les fichiers suivants sont extraits du guide complet `instructions_flutter_api.txt`. Copiez leur contenu dans les chemins respectifs de votre projet Flutter :

    *   **`lib/models/article.dart`**:
        ```dart
        // Contenu du modèle Article (issu de instructions_flutter_api.txt)
        class Article {
          final int id;
          final String name;
          final String description;
          // Ajoutez d'autres champs si votre modèle Article dans Flutter est plus complet
          // (ex: prix, quantite), et ajustez fromJson.

          Article({required this.id, required this.name, required this.description});

          factory Article.fromJson(Map<String, dynamic> json) {
            return Article(
              id: json['id'],
              name: json['name'] ?? 'Nom non disponible',
              description: json['description'] ?? 'Description non disponible',
              // prix: (json['prix'] as num?)?.toDouble(), // Exemple si prix est inclus
              // quantite: json['quantite'] as int?,     // Exemple si quantite est inclus
            );
          }
        }
        ```

    *   **`lib/services/api_service.dart`**:
        ```dart
        // Collez ici l'intégralité du code de ApiService.dart fourni dans instructions_flutter_api.txt
        // Assurez-vous que les URLs de base sont correctes :
        // String get _androidBaseUrl => 'http://10.0.2.2:8000/api';
        // String get _iosBaseUrl => 'http://127.0.0.1:8000/api';
        ```
        *(Le code complet de `ApiService.dart` se trouve dans `instructions_flutter_api.txt`)*

    *   **`lib/screens/article_screen.dart`**:
        ```dart
        // Collez ici l'intégralité du code de ArticleScreen.dart fourni dans instructions_flutter_api.txt
        ```
        *(Le code complet de `ArticleScreen.dart` se trouve dans `instructions_flutter_api.txt`)*
        *N.B. : Assurez-vous que le modèle `Article` utilisé dans `ArticleScreen.dart` correspond à celui défini dans `lib/models/article.dart`, notamment les champs `prix` et `quantite` s'ils sont affichés ou utilisés.*

    *   **`lib/main.dart`**: Mettez à jour votre `main.dart` pour lancer `ArticleScreen` :
        ```dart
        import 'package:flutter/material.dart';
        import 'screens/article_screen.dart'; // Ajustez le chemin si nécessaire

        void main() {
          runApp(const MyApp());
        }

        class MyApp extends StatelessWidget {
          const MyApp({super.key});

          @override
          Widget build(BuildContext context) {
            return MaterialApp(
              title: 'Flutter Laravel API Test',
              theme: ThemeData(
                primarySwatch: Colors.blue,
                useMaterial3: true, // Optionnel, pour un look plus moderne
              ),
              home: const ArticleScreen(),
            );
          }
        }
        ```

4.  **Ajouter les Dépendances (`pubspec.yaml`) :**
    Ouvrez `pubspec.yaml` et ajoutez (ou vérifiez) :
    ```yaml
    dependencies:
      flutter:
        sdk: flutter
      http: ^1.2.0 # Ou la dernière version compatible
      shared_preferences: ^2.2.0 # Ou la dernière version compatible
    ```
    Exécutez ensuite :
    ```bash
    flutter pub get
    ```

5.  **Configurer les Permissions Réseau (pour développement HTTP) :**
    *   **Android (`android/app/src/main/AndroidManifest.xml`) :**
        Ajoutez `android:usesCleartextTraffic="true"` dans la balise `<application>` et assurez-vous que `<uses-permission android:name="android.permission.INTERNET" />` est présent.
        ```xml
        <manifest xmlns:android="http://schemas.android.com/apk/res/android">
            <uses-permission android:name="android.permission.INTERNET" />
            <application
                android:label="test_api_flutter_app"
                android:name="${applicationName}"
                android:icon="@mipmap/ic_launcher"
                android:usesCleartextTraffic="true">
                <!-- ... reste de la configuration ... -->
            </application>
        </manifest>
        ```
    *   **iOS (`ios/Runner/Info.plist`) :**
        Ajoutez les clés pour `NSAppTransportSecurity` afin d'autoriser les charges HTTP.
        ```xml
        <key>NSAppTransportSecurity</key>
        <dict>
            <key>NSAllowsArbitraryLoads</key>
            <true/>
        </dict>
        ```
    *(Rappel : Ces configurations sont pour le développement. Utilisez HTTPS en production.)*

6.  **Vérifier l'URL de Base de l'API dans `ApiService.dart` :**
    Ouvrez `lib/services/api_service.dart`. Confirmez que `_androidBaseUrl` est `http://10.0.2.2:8000/api` (pour l'émulateur Android) et `_iosBaseUrl` est `http://127.0.0.1:8000/api` (pour le simulateur iOS). Si votre API Laravel tourne sur un port différent, ajustez-le ici.

7.  **Lancer l'Application Flutter :**
    ```bash
    flutter run
    ```
    Choisissez l'appareil (émulateur/simulateur/physique) sur lequel exécuter.

## Étape 3: Tester l'Intégration

1.  **Connexion :**
    - L'application Flutter devrait afficher l'écran `ArticleScreen`.
    - Utilisez les identifiants de l'utilisateur de test créé par le seeder :
        - **Email :** `test@example.com`
        - **Mot de passe :** `password`
        - **Nom de l'appareil :** `FlutterApp` (ou ce que vous voulez)
    - Cliquez sur "Se connecter".

2.  **Opérations CRUD sur les Articles :**
    - Si la connexion réussit, la liste des articles (initialement vide si aucun article n'a été créé) devrait s'afficher.
    - **Créer un article :** Utilisez le bouton "+" (FloatingActionButton) pour ajouter un nouvel article. Remplissez les champs (nom, description, prix, quantité).
    - **Afficher les articles :** Vérifiez que le nouvel article apparaît dans la liste.
    - **Supprimer un article :** Utilisez l'icône de corbeille pour supprimer un article.
    - *(La mise à jour d'article n'est pas explicitement implémentée dans l'interface utilisateur de cet exemple `ArticleScreen.dart` de base, mais la méthode existe dans `ApiService.dart`.)*

3.  **Vérifier dans la Base de Données Laravel (Optionnel) :**
    - Vous pouvez utiliser un outil de navigation de base de données pour SQLite (comme DB Browser for SQLite) pour ouvrir `database/database.sqlite` et voir les changements en direct.
    - Alternativement, utilisez `php artisan tinker` :
      ```bash
      php artisan tinker
      >>> App\Models\Article::all(); // Pour voir tous les articles
      >>> App\Models\User::where('email', 'test@example.com')->first(); // Pour voir l'utilisateur
      ```

## Dépannage Courant

- **L'API ne répond pas / Flutter ne peut pas se connecter :**
    - Vérifiez que le serveur Laravel (`php artisan serve`) est bien en cours d'exécution.
    - Assurez-vous que l'URL de base dans `ApiService.dart` est correcte pour votre plateforme (Android : `10.0.2.2`, iOS : `127.0.0.1`, même port que `artisan serve`).
    - Contrôlez les permissions réseau dans `AndroidManifest.xml` (Android) et `Info.plist` (iOS).
    - Vérifiez qu'aucun pare-feu ne bloque la connexion entre votre appareil/émulateur et votre machine hôte.
- **Erreurs 401 Non Autorisé (Unauthorized) :**
    - Le token n'est pas envoyé ou est incorrect. Vérifiez la logique de connexion et le stockage/envoi du token dans `ApiService.dart`.
- **Erreurs 422 Entité Non Traitable (Unprocessable Entity) :**
    - Les données envoyées à l'API ne passent pas la validation de Laravel (ex: champs manquants, format incorrect). Vérifiez les `StoreArticleRequest` / `UpdateArticleRequest` de Laravel et les données envoyées par Flutter.
- **Problèmes CORS :**
    - Normalement pas un souci pour mobile vers localhost. Si vous testez une version web de Flutter et que l'API est sur un port différent, vous pourriez avoir besoin de configurer les CORS dans Laravel (`config/cors.php`).

Ce guide devrait vous aider à tester votre configuration API Laravel et Flutter en local. Bon test !
```
