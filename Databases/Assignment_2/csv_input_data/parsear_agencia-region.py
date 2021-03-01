import pandas as pd

my_filtered_csv = pd.read_csv("datos/agencia_agenciaregion_tour.csv",
                              usecols=['aid', 'rid'])

my_filtered_csv = my_filtered_csv.drop_duplicates(subset=['aid', 'rid'])

my_filtered_csv.to_csv("Agencias_Regiones.csv", index=False)
