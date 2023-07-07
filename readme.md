## Documentație - Chat Bot Telegram pentru partajarea Planului zilnic pe Mail la administratia Unisim

Acest cod reprezintă un bot Telegram care permite utilizatorilor să partajeze Planul zilnic printr-un mesaj, care este apoi transmis prin e-mail la Administratia Unisim.

### Configurarea inițială

1. Asigurați-vă că aveți instalată biblioteca necesară pentru a utiliza funcționalitățile Telegram Bot API. Acest lucru poate fi realizat prin includerea fișierului `autoload.php`.
2. Creați un fișier `env.php` și indicați în acesta detaliile de conectare la baza de date (utilizator, parolă, schemă). De asemenea, specificați și parametrul `G_LIST_KEY_PLAN`, care este o matrice ce conține lista cuvintelor/frazelor cheie pentru căutarea mesajului care conține Planul zilnic.

### Funcționalități

1. Comanda `/start`: Răspunsul la această comandă este un mesaj de bun venit.
2. Comanda `/help`: Răspunsul la această comandă furnizează informații despre utilizarea botului și listează cuvintele cheie pentru căutarea Planului zilnic.
3. Comanda `/info`: Răspunsul la această comandă furnizează ID-ul utilizatorului și numele acestuia.
4. Mesajul cu Planul zilnic: Atunci când botul detectează un mesaj care conține cuvintele cheie specifice, acesta va salva mesajul într-o bază de date Oracle în tabela `TELEGRAM_HR_CHAT`. De asemenea, există un trigger pe tabela `TELEGRAM_HR_CHAT` care inserează utilizatorii Telegram noi în tabela `TELEGRAM_HR_USERS`, dacă aceștia nu sunt deja înregistrați. În tabela `TELEGRAM_HR_USERS`, există un câmp `OTRS_USER` în care trebuie să specificați manual ID-ul utilizatorului din Otrs. Acest ID va fi utilizat ulterior pentru a trimite mesajul pe E-Mail către utilizatorul respectiv. Dacă există o legătură între utilizatorul Telegram și Otrs, tema mesajului de E-Mail va conține numele complet al utilizatorului, în caz contrar va conține doar numele de utilizator din Telegram.
5. Job de trimitere a mesajelor: În baza de date există un job care rulează la fiecare 3 minute și verifică și trimite mesajele care nu au fost încă trimise din tabela `TELEGRAM_HR_CHAT`. Procedura utilizată în job este `pkg_ticket_mail.send_mail_plan_zilnic`.

Este important să aveți o bază de date Oracle configurată corespunzător și să asigurați că structurile tabelare menționate mai sus există în baza de date.

Pentru orice întrebări sau probleme legate de utilizarea botului Telegram, vă rugăm să consultați documentația oficială a API-ului Telegram Bot.

## RAPORT ZILNIC

### Functionalitatea fisierului send_message.php
Acest fișier primește un parametru ID prin metoda GET și caută în baza de date (DB) în tabela vraport_hr_otrs mesajul care trebuie trimis de către chat bot și ID-ul chat-ului în care se trimite acest mesaj.

## Tabelul vraport_hr_otrs

Tabela vraport_hr_otrs este completată la trimiterea raportului zilnic din OTRS cu comanda `#raportulmeuzilnic`. Completarea acestui tabel se face atât când se trimite raportul automat, cât și când se trimite manual. Funcția care completează tabela este `pkg_ticket_mail.fill_RAPORT_HR_OTRS`.

## Trimiterea mesajului

Pentru trimiterea mesajului, se folosește procedura `pkg_ticket_mail.send_message_hr`. Această procedură este utilizată și în momentul trimiterii automate a rapoartelor zilnice, mesajele fiind trimise în chat.

## Tabelul TELEGRAM_HR_USERS

Pentru ca mesajele să fie trimise cu succes, este necesar ca în tabelul TELEGRAM_HR_USERS să fie completate datele necesare. Aceste date includ:
- `telegram_user` - utilizatorul angajat din Telegram
- `otrs_user` - ID-ul angajatului din OTRS
- `id_chat_telegram` - ID-ul chat-ului Telegram

Valorile pentru `telegram_user` și `id_chat_telegram` pot fi obținute în chat-ul Telegram prin apelarea comenzii `/info`.

Tabelul TELEGRAM_HR_USERS poate fi completat atât direct din baza de date, cât și din aplicația UNA (Backoffice) din forma 20->20.6, 20.6 Lista OTRS - Telegram pentru Planuri de lucru.

