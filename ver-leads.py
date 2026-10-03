"""
ACURIA - Visualizador e Exportador Local de Leads
Execute este script para ver todos os contatos salvos no banco SQLite local.
"""
import sqlite3
import os
import sys

db_path = os.path.join(os.path.dirname(__file__), 'database', 'leads.sqlite')

if not os.path.exists(db_path):
    print("\n[INFO] O arquivo de banco de dados 'database/leads.sqlite' ainda não foi criado.")
    print("Ele é gerado automaticamente no primeiro envio do formulário de contato.\n")
    sys.exit(0)

try:
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    cursor.execute("SELECT id, data_hora, nome, empresa, email, linha, fase FROM leads ORDER BY id DESC")
    rows = cursor.fetchall()
    conn.close()

    if not rows:
        print("\n[INFO] O banco de dados está pronto, mas ainda não possui contatos registrados.\n")
    else:
        print("\n" + "="*80)
        print(f" ACURIA | LEADS NO BANCO DE DADOS LOCAL (Total: {len(rows)})")
        print("="*80)
        for r in rows:
            print(f"ID: {r[0]} | Data: {r[1]}")
            print(f"Nome: {r[2]} | Empresa: {r[3]}")
            print(f"E-mail: {r[4]} | Linha: {r[5]}")
            print(f"Fase/Notas: {r[6]}")
            print("-" * 80)
except Exception as e:
    print(f"\n[ERRO] Não foi possível ler o banco de dados: {e}\n")
