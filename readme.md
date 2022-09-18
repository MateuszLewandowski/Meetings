# Wprowadzenie

W aplikacji zaimplementowano trzy kontrolery odpowiadające odpowiednio za:
1. [Utworzenie obiektu użytkownika] - "/user/create" - pola wymagane: email
2. [Utworzenie obiektu spotkania/wydarzenia] - "/meeting/create" - pola wymagane: name, date, owner_id, participants_limit
3. [Utworzenie relacji n:n użytkownik-wydarzenie] - "/meeting/participant/register" - pola wymagane: user_id, meeting_id

# Request Lifecycle
1. Kontroler inicjalizuje dedykowany request, który przyjmuje wymagane parametry. Następnie są one walidowane metodą chain of resposibility.
2. Kontroler wywołuje wstrzyknięty serwis, przekazując doń wybrane parametry.
3. Serwis odpowiada za:
3.1. Bardziej złożoną walidację, np. sprawdzenie, czy użytkownik/wydarzenie istnieje.
3.2. Utworzenie obiektu na podstawie przekazanych parametrów
3.3. Wywołanie metody w repozytorium
3.4. Wysłanie notyfikacji, zwrócenie wyjątku 
3.5. Zwrócenie utworzonego obiektu.
4. Kontroler zwraca w odpowiedzi komunikat/DTO, kod HTTP - PSR7

# Testy
Zaimplementowano wybrane unit/feature tests, stanowiące przykład pokrycia aplikacji.
1. CreateMeetingFactoryTest - Przykład przetestowania fabryki modelu Meeting.
2. CreateMeetingControllerTest - Odwrócenie scenariusza (od strony klienta), polega na utworzeniu użytkownika w testowej bazie danych, a następnie utworzeniu wydarzenia, którego jest ownerem. Do implementacji wykorzystano klienta GuzzleHTTP.

# Todo
1. Każdorazowe uruchomienie serwera powoduje nadanie losowego portu (nadanie statycznego).