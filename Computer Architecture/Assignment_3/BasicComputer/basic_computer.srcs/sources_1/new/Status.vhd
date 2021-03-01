library IEEE;
use IEEE.STD_LOGIC_1164.ALL;
use IEEE.std_logic_unsigned.all;
use IEEE.numeric_std.all;

entity Status is
    Port ( 
           clock    : in  std_logic;
           c        : in  std_logic;
           z        : in  std_logic;
           n        : in  std_logic;
           o        : in  std_logic;
           dataout  : out std_logic_vector (3 downto 0));
end Status;

architecture Behavioral of Status is

signal write_status : std_logic_vector(1 downto 0);

begin

status_prosses : process (clock)
    begin
      if (rising_edge(clock)) then
        if (write_status = "00") then
            write_status <= "01";   
            dataout(0) <= z;
                                        dataout(1) <= n;
                                        dataout(2) <= c;
                                        dataout(3) <= o;
        elsif (write_status = "01") then
            write_status <= "10";
            
        elsif (write_status = "10") then
               write_status <= "00";
            
                                       
      end if;
      end if;
    end process;

end Behavioral;