library IEEE;
use IEEE.STD_LOGIC_1164.ALL;


entity MuxALU is
    Port ( in0  : in std_logic_vector (11 downto 0);
           in1  : in std_logic_vector (11 downto 0);
           in2  : in std_logic_vector (11 downto 0);
           in3  : in std_logic_vector (11 downto 0);
           in4  : in std_logic_vector (11 downto 0);
           in5  : in std_logic_vector (11 downto 0);
           sel  : in std_logic_vector (2 downto 0);
           out_mux  : out std_logic_vector (11 downto 0));
end MuxALU;

architecture Behavioral of MuxALU is

begin
    with sel select -- Se definen senales arbitrarias para el mux!
    out_mux <=   in0 when "000",
                 in1 when "001",
                 in2 when "010",
                 in3 when "011",
                 in4 when "100",
                 in5 when "101",
                 (others => '0') when others; -- Por si acaso

end Behavioral;
