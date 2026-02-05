CREATE TABLE b_custom_notes (
                         id SERIAL PRIMARY KEY,
                         title VARCHAR(255) NOT NULL,
                         content TEXT,
                         created_at TIMESTAMP DEFAULT now(),
                         updated_at TIMESTAMP DEFAULT now()
);

-- Функция для обновления updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
   NEW.updated_at = now();
RETURN NEW;
END;
$$ language 'plpgsql';

-- Триггер для таблицы
CREATE TRIGGER trigger_update_updated_at
    BEFORE UPDATE ON b_custom_notes
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();
