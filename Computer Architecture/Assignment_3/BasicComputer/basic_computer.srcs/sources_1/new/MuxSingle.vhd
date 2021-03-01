library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

-- Rafa

-- Define un Multiplexor con solo un bit de senal  de control-> Dos entradas
-- Las entradas son de 12 bits


entity MuxSingle is
    Port ( in0  : in std_logic_vector (11 downto 0);
           in1  : in std_logic_vector (11 downto 0);
           sel  : in STD_LOGIC;
           out_mux  : out std_logic_vector (11 downto 0));

end MuxSingle;

architecture Behavioral of MuxSingle is

begin

with sel select -- Se definen senales arbitrarias para el mux!
    out_mux <=   in0 when '0',
                 in1 when '1',
                 (others => '0') when others; -- Por si acaso

end Behavioral;