- `src/CategoryStatsOptimized.php` — статический метод `process(array $data): array`.
- `tests/CategoryStatsOptimizedTest.php` — тесты для проверки экстремумов, сортировки и поведения при пустом входе.

алгоритм делает одну основную итерацию по всем элементам `data['category']`. В этой итерации:
вычисляет текущие `max` и `min` для `bot_count`,
собирает плоский список записей с `priority`, `user_count` и `bot_count`.
после этого список сортируется по `priority` через `usort`.