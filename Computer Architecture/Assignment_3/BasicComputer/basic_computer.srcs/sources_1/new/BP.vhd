library IEEE;
use IEEE.STD_LOGIC_1164.ALL;
use IEEE.std_logic_unsigned.all;

entity BP is
    Port ( 
           clock    : in  std_logic;
           load       : in  std_logic;
           datain   : in  std_logic_vector (11 downto 0);   -- Se�ales de entrada de datos.
           dataout  : out std_logic_vector (11 downto 0));
end BP;

architecture Behavioral of BP is

signal bp : std_logic_vector(11 downto 0) := (others => '1');

begin

sp_prosses : process (clock)
    begin
      if (rising_edge(clock)) then
        if (load = '1') then
            bp <= datain;
        end if;
      end if;
    end process;
    
dataout <= bp;

end Behavioral;
