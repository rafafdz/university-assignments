library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

-- Rafa

-- Multiplexor con 2 bits de senales -> 4 Entradas

entity MuxDouble is
    Port ( in0  : in std_logic_vector (11 downto 0);
           in1  : in std_logic_vector (11 downto 0);
           in2  : in std_logic_vector (11 downto 0);
           in3  : in std_logic_vector (11 downto 0);
           sel  : in std_logic_vector (1 downto 0);
           out_mux  : out std_logic_vector (11 downto 0));
end MuxDouble;

architecture Behavioral of MuxDouble is

begin
    with sel select -- Se definen senales arbitrarias para el mux!
    out_mux <=   in0 when "00",
                 in1 when "01",
                 in2 when "10",
                 in3 when "11",
                 (others => '0') when others; -- Por si acaso

end Behavioral;