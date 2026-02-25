# TODO-Liste: WebmasterGram Funktionen

## 1. Authentifizierung & Nutzerverwaltung
- [x] Nutzerregistrierung mit **Username** und **E-Mail-Adresse**.
- [x] Nutzer-Login mit Username oder E-Mail + Passwort.
- [x] E-Mail-Verifizierung nach der Registrierung (kein Posten vor Bestätigung).
- [x] Passwort zurücksetzen via E-Mail-Link.
- [x] Anmeldung via **GitHub-Account**.
- [x] Nutzerprofil erstellen.

## 2. Profil
- [x] Anzeige von **Profilbild** und **Username**.
- [x] Profilbild per **Datei-Upload** setzen.
- [x] Profilbild und Username editierbar machen.
- [x] Anzeige von **Anzahl der Posts, Follower und gefolgten Nutzer**.
- [x] Anzeige aller eigenen Posts im Profil.

## 3. Posts
- [x] Erstellung eines neuen Posts mit **Beschreibung** und optionalem Bild.
- [x] Anzeige von Posts anderer Nutzer auf der Timeline.
- [x] Liken und Entliken von Posts anderer Nutzer.
- [x] Anzeige von **Likes-Zahl** und **Upload-Datum** bei jedem Post.
- [x] Löschen von eigenen Posts (inklusive Bilddatei).

## 4. Timeline & Feed
- [x] Timeline als erste Seite nach Login.
- [x] Anzeige von eigenen Posts + Posts gefolgter Nutzer.
- [x] Anzeige einer **Liste aller gefolgten Nutzer** neben der Timeline.

## 5. Soziales Netzwerk
- [x] Nutzer können andere Nutzer **folgen** und **entfolgen**.
- [x] Aufrufbare Profile anderer Nutzer.
- [x] Möglichkeit, nach Nutzern via **Username** zu suchen.
- [x] E-Mail-Benachrichtigung bei neuem Follower (mit **Events** und **Queues**).

## 6. Code & Architektur
- [ ] Fehler und Ausnahmen angemessen behandeln.
- [ ] Saubere **Code-Struktur** mit Kommentaren.
- [ ] Einhaltung von **Namenskonventionen**, SOLID & DRY-Prinzipien.
- [ ] Vermeidung von **N+1-Problemen** und unnötigen Datenbank-Queries.
- [ ] Optimierung langsamer Queries.

## 7. Sonstiges
- [ ] Design ist sekundär, App sollte **realistisch verwendbar** sein.
- [ ] Sicherstellen, dass Uploads und Medien korrekt angezeigt werden.