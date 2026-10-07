# PayPal Donation Extension (skouat/ppde) — version modifiée

Extension phpBB ajoutant une page de dons PayPal au forum.
Basée sur [skouat/ext_paypal_donation](https://github.com/Skouat/ext_paypal_donation) (branche `develop-4.0.x`), avec des modifications locales.

## Prérequis

- phpBB 3.3.11 ou supérieur (< 3.4)
- PHP 7.2 ou supérieur, extensions `curl`, `intl`, `json`, `openssl`
- Dépendance Composer `paypal/paypal-server-sdk` 2.3.0 (dossier `vendor/`)

## Installation / mise à jour

1. Désactiver l'extension dans l'ACP (Personnaliser → Gérer les extensions).
2. Copier le contenu de l'archive dans `ext/skouat/ppde/` en écrasant les fichiers existants (conserver le dossier `vendor/`).
3. Réactiver l'extension : la migration ajoute le nouveau paramètre.
4. Vider le cache du forum.

## Affichage selon les groupes

ACP → Extensions → PayPal Donation → Paramètres généraux → « Affichage selon les groupes ».

- Cocher les groupes autorisés à voir la partie don PayPal : lien « Faire un don », page de dons et statistiques des dons sur l'index.
- Aucun groupe coché : pas de restriction (comportement d'origine).
- La permission « Peut faire un don » reste nécessaire en plus de l'appartenance à un groupe coché.
- La liste des donateurs n'est pas concernée : elle garde sa propre permission.

## Journal des versions

### 4.0.4 — 2026-10-07

- Ajout : choix des groupes d'utilisateurs autorisés à voir la partie don PayPal (paramètre `ppde_display_groups`, migration `v404_m1_display_groups`).
- Ajout : nouvelle présentation des pages de l'ACP (blocs arrondis, légendes avec icônes, badge de version).
- Ajout : ce README.

### 4.0.3

- Version d'origine (Skouat, branche `develop-4.0.x`).
