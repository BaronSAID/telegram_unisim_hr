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