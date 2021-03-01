library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

-- Rafa

entity MuxAddress is
    Port ( in_sp : in std_logic_vector (11 downto 0);
           in_add_sub : in std_logic_vector (11 downto 0);
           in_pc : in std_logic_vector (11 downto 0);
           in_reg : in std_logic_vector (11 downto 0);
           in_lit: in std_logic_vector (11 downto 0); 
           sel: in std_logic_vector (3 downto 0);
           out_mem : out std_logic_vector (11 downto 0));
end MuxAddress;

architecture Behavioral of MuxAddress is

begin

with sel select -- Las señales van en orden segun el esquema del pdf de la tarea
    out_mem <=   in_add_sub when "0000",
                 in_sp when "0001",
                 in_lit when "0010",
                 in_pc when "0011",
                 in_reg when "0100",
                 (others => '0') when others;

end Behavioral;